<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create book_copies table
        Schema::create('book_copies', function (Blueprint $table) {
            $table->id();
            $table->string('barcode_id')->unique();
            $table->foreignId('book_id')->nullable()->constrained('books')->onDelete('cascade');
            $table->enum('status', ['Unassigned', 'Available', 'Issued', 'Lost', 'Damaged', 'Retired'])->default('Available');
            $table->timestamp('barcode_printed_at')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamps();
        });

        // 2. Add book_copy_id to transactions table
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('book_copy_id')->nullable()->after('member_id')->constrained('book_copies')->onDelete('cascade');
        });

        // 3. Migrate existing books to copies and link transactions
        $books = DB::table('books')->get();
        
        // Find max barcode to continue sequential generation
        $maxBarcode = DB::table('books')->where('barcode_id', 'like', 'LIB-%')->orderBy('barcode_id', 'desc')->value('barcode_id');
        $barcodeCounter = $maxBarcode ? ((int) substr($maxBarcode, 4)) + 1 : 1;

        foreach ($books as $book) {
            $totalCopies = $book->total_copies ?? 1;
            
            // Fetch all transactions for this book
            $transactions = DB::table('transactions')
                ->where('book_id', $book->id)
                ->orderBy('status', 'asc') // Active statuses (Issued, Overdue) first alphabetically
                ->get();

            // Active transactions (Issued or Overdue)
            $activeTransactions = $transactions->whereIn('status', ['Issued', 'Overdue'])->values();
            // Returned / Completed transactions
            $completedTransactions = $transactions->whereNotIn('status', ['Issued', 'Overdue'])->values();

            $activeCount = $activeTransactions->count();

            // Ensure we create at least enough copies to cover active checkouts
            $copiesToCreate = max($totalCopies, $activeCount);
            if ($copiesToCreate === 0 && $book->barcode_id) {
                $copiesToCreate = 1;
            }

            $firstCopyId = null;

            for ($i = 0; $i < $copiesToCreate; $i++) {
                // Determine barcode
                if ($i === 0 && !empty($book->barcode_id)) {
                    $barcode = $book->barcode_id;
                } else {
                    do {
                        $barcode = 'LIB-' . str_pad($barcodeCounter, 6, '0', STR_PAD_LEFT);
                        $barcodeCounter++;
                    } while (
                        DB::table('book_copies')->where('barcode_id', $barcode)->exists() ||
                        DB::table('books')->where('barcode_id', $barcode)->exists()
                    );
                }

                // Determine copy status: link active transactions to copies
                $copyStatus = 'Available';
                $linkedTransaction = null;
                if ($i < $activeCount) {
                    $linkedTransaction = $activeTransactions[$i];
                    $copyStatus = $linkedTransaction->status; // Issued or Overdue
                }

                // Insert copy
                $copyId = DB::table('book_copies')->insertGetId([
                    'barcode_id' => $barcode,
                    'book_id' => $book->id,
                    'status' => $copyStatus,
                    'barcode_printed_at' => ($i === 0) ? $book->barcode_printed_at : null,
                    'assigned_at' => $book->created_at,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                if ($i === 0) {
                    $firstCopyId = $copyId;
                }

                // Link active transaction to this specific copy
                if ($linkedTransaction) {
                    DB::table('transactions')
                        ->where('id', $linkedTransaction->id)
                        ->update(['book_copy_id' => $copyId]);
                }
            }

            // Link any completed / historical transactions of this book to the first copy
            if ($firstCopyId) {
                foreach ($completedTransactions as $completedTx) {
                    DB::table('transactions')
                        ->where('id', $completedTx->id)
                        ->update(['book_copy_id' => $firstCopyId]);
                }
            }
        }

        // 4. Drop copies and barcode columns from books table
        Schema::table('books', function (Blueprint $table) {
            // Drop unique constraint first to prevent SQLite alter table errors
            $table->dropUnique('books_barcode_id_unique');
            $table->dropColumn(['barcode_id', 'barcode_printed_at', 'total_copies', 'available_copies']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add columns back to books table
        Schema::table('books', function (Blueprint $table) {
            $table->string('barcode_id')->nullable()->after('id');
            $table->timestamp('barcode_printed_at')->nullable()->after('barcode_id');
            $table->integer('total_copies')->default(1)->after('author_id');
            $table->integer('available_copies')->default(1)->after('total_copies');
        });

        // Restore data from book_copies back to books
        $copies = DB::table('book_copies')->orderBy('id', 'asc')->get();
        foreach ($copies as $copy) {
            $book = DB::table('books')->where('id', $copy->book_id)->first();
            if ($book && empty($book->barcode_id)) {
                DB::table('books')->where('id', $copy->book_id)->update([
                    'barcode_id' => $copy->barcode_id,
                    'barcode_printed_at' => $copy->barcode_printed_at,
                ]);
            }
        }

        // Drop foreign keys and book_copies table
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('book_copy_id');
        });

        Schema::dropIfExists('book_copies');
    }
};

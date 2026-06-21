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
        Schema::table('books', function (Blueprint $table) {
            $table->string('barcode_id')->nullable()->after('id');
            $table->string('isbn')->nullable()->change();
        });

        // Generate barcode_id for existing books
        $books = DB::table('books')->orderBy('id', 'asc')->get();
        $counter = 1;
        foreach ($books as $book) {
            $barcode = 'LIB-' . str_pad($counter, 6, '0', STR_PAD_LEFT);
            DB::table('books')->where('id', $book->id)->update(['barcode_id' => $barcode]);
            $counter++;
        }

        // Now make barcode_id unique and not null
        Schema::table('books', function (Blueprint $table) {
            $table->string('barcode_id')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropUnique(['barcode_id']);
            $table->dropColumn('barcode_id');
            $table->string('isbn')->nullable(false)->change();
        });
    }
};

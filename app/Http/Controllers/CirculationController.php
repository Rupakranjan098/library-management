<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Member;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CirculationController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['book', 'bookCopy', 'member']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('bookCopy', function ($cq) use ($search) {
                    $cq->where('barcode_id', 'like', "%{$search}%")
                       ->orWhereHas('book', function ($bq) use ($search) {
                           $bq->where('title', 'like', "%{$search}%");
                       });
                })->orWhereHas('member', function ($mq) use ($search) {
                    $mq->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Date range filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
            $query->whereBetween('issue_date', [$startDate, $endDate]);
        }

        $transactions = $query->latest()->paginate(15)->withQueryString();

        return view('circulation.index', compact('transactions'));
    }

    public function showIssueForm()
    {
        $members = Member::orderBy('name')->get();
        return view('circulation.issue', compact('members'));
    }

    public function issue(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'barcode_id' => 'required|string',
        ]);

        return DB::transaction(function () use ($validated) {
            $copy = BookCopy::findByBarcode($validated['barcode_id']);

            if (!$copy) {
                return back()->withErrors(['barcode_id' => 'No copy found with the scanned barcode ID.'])->withInput();
            }

            if ($copy->status !== 'Available') {
                return back()->withErrors(['barcode_id' => "This book copy is not available (Current status: {$copy->status})."])->withInput();
            }

            if (empty($copy->book_id)) {
                return back()->withErrors(['barcode_id' => 'This copy is unassigned and cannot be checked out.'])->withInput();
            }

            $book = $copy->book;

            // Check if this member already has an active (unreturned) transaction for this exact book title
            $activeTransaction = Transaction::where('member_id', $validated['member_id'])
                ->where('book_id', $book->id)
                ->whereIn('status', ['Issued', 'Overdue'])
                ->first();

            if ($activeTransaction) {
                return back()->withErrors(['barcode_id' => 'This member has already issued a copy of this book title and has not returned it yet.'])->withInput();
            }

            $loanPeriod = config('circulation.loan_period', 14);
            $issueDate = now();
            $dueDate = now()->addDays($loanPeriod);

            // Create transaction
            Transaction::create([
                'book_id' => $book->id,
                'book_copy_id' => $copy->id,
                'member_id' => $validated['member_id'],
                'issue_date' => $issueDate,
                'due_date' => $dueDate,
                'status' => 'Issued',
                'fine_amount' => 0.00,
            ]);

            // Update copy status
            $copy->update(['status' => 'Issued']);

            return redirect()->route('circulation.index')->with('success', sprintf(
                'Book "%s" (Copy: %s) has been successfully issued to %s. Due date: %s.',
                $book->title,
                $copy->barcode_id,
                Member::find($validated['member_id'])->name,
                $dueDate->format('Y-m-d')
            ));
        });
    }

    public function showReturnForm(Request $request)
    {
        $activeTransaction = null;
        $copy = null;
        $book = null;
        $fineAmount = 0.00;
        $daysOverdue = 0;

        if ($request->filled('barcode_id')) {
            $copy = BookCopy::findByBarcode($request->input('barcode_id'));
            if ($copy) {
                $book = $copy->book;
                $activeTransaction = Transaction::with('member')
                    ->where('book_copy_id', $copy->id)
                    ->whereIn('status', ['Issued', 'Overdue'])
                    ->first();

                if ($activeTransaction) {
                    if (now()->greaterThan($activeTransaction->due_date)) {
                        $daysOverdue = abs(now()->diffInDays($activeTransaction->due_date));
                        $fineRate = config('circulation.fine_rate', 2.00);
                        $fineAmount = $daysOverdue * $fineRate;
                    }
                }
            }
        }

        return view('circulation.return', compact('activeTransaction', 'copy', 'book', 'fineAmount', 'daysOverdue'));
    }

    public function returnBook(Request $request)
    {
        $validated = $request->validate([
            'barcode_id' => 'required|string',
        ]);

        return DB::transaction(function () use ($validated) {
            $copy = BookCopy::findByBarcode($validated['barcode_id']);

            if (!$copy) {
                return back()->withErrors(['barcode_id' => 'No copy found with the scanned barcode ID.'])->withInput();
            }

            $activeTransaction = Transaction::where('book_copy_id', $copy->id)
                ->whereIn('status', ['Issued', 'Overdue'])
                ->first();

            if (!$activeTransaction) {
                return back()->withErrors(['barcode_id' => 'This book copy is not currently issued (no open transaction found).'])->withInput();
            }

            $fineAmount = 0.00;
            if (now()->greaterThan($activeTransaction->due_date)) {
                $daysOverdue = abs(now()->diffInDays($activeTransaction->due_date));
                $fineRate = config('circulation.fine_rate', 2.00);
                $fineAmount = $daysOverdue * $fineRate;
            }

            // Update transaction
            $activeTransaction->update([
                'return_date' => now(),
                'status' => 'Returned',
                'fine_amount' => $fineAmount,
            ]);

            // Update copy status
            $copy->update(['status' => 'Available']);

            $book = $copy->book;
            $successMsg = sprintf('Book "%s" (Copy: %s) has been successfully returned.', $book->title, $copy->barcode_id);
            if ($fineAmount > 0) {
                $successMsg .= sprintf(' Overdue fine of ₹%s has been recorded.', number_format($fineAmount, 2));
            }

            return redirect()->route('circulation.index')->with('success', $successMsg);
        });
    }

    public function overdueDashboard(Request $request)
    {
        $query = Transaction::with(['book', 'bookCopy', 'member'])->where('status', 'Overdue');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('bookCopy', function ($cq) use ($search) {
                    $cq->where('barcode_id', 'like', "%{$search}%")
                       ->orWhereHas('book', function ($bq) use ($search) {
                           $bq->where('title', 'like', "%{$search}%");
                       });
                })->orWhereHas('member', function ($mq) use ($search) {
                    $mq->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%")
                       ->orWhere('phone', 'like', "%{$search}%");
                });
            });
        }

        $overdueTransactions = $query->latest()->paginate(15)->withQueryString();

        return view('circulation.overdue', compact('overdueTransactions'));
    }
}

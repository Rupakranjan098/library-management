<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Member;
use App\Models\BorrowRecord;
use App\Models\Category;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Automatically calculate and update overdue records
        \App\Models\Transaction::where('status', 'Issued')
            ->where('due_date', '<', now())
            ->each(function ($transaction) {
                $transaction->update(['status' => 'Overdue']);
                if ($transaction->bookCopy) {
                    $transaction->bookCopy->update(['status' => 'Overdue']);
                }
            });

        // Top level stats
        $totalBooks = \App\Models\BookCopy::where('status', '!=', 'Retired')->count();
        $totalMembers = Member::count();
        $booksBorrowed = \App\Models\Transaction::whereIn('status', ['Issued', 'Overdue'])->count();
        $overdueBooks = \App\Models\Transaction::where('status', 'Overdue')->count();
        $totalFines = \App\Models\Transaction::sum('fine_amount');

        // Parse date range
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfDay();

        if ($request->filled('date_range')) {
            $parts = explode(' to ', $request->input('date_range'));
            if (count($parts) === 2) {
                try {
                    $startDate = Carbon::parse($parts[0])->startOfDay();
                    $endDate = Carbon::parse($parts[1])->endOfDay();
                } catch (\Exception $e) {}
            }
        }
        $formattedRange = $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d');

        // Monthly trends
        $newBooksThisMonth = Book::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        $newMembersThisMonth = Member::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        $borrowedThisMonth = \App\Models\Transaction::whereMonth('issue_date', Carbon::now()->month)
            ->whereYear('issue_date', Carbon::now()->year)
            ->count();
        $overdueThisMonth = \App\Models\Transaction::where('status', 'Overdue')
            ->whereMonth('due_date', Carbon::now()->month)
            ->whereYear('due_date', Carbon::now()->year)
            ->count();

        // Books by Category for Donut Chart
        $categories = Category::withCount('books')->get();
        $categoryLabels = $categories->pluck('name')->toArray();
        $categoryData = $categories->pluck('books_count')->toArray();

        // Books Borrowed Over Time for Line Chart (dynamic 30 days data)
        $chartDates = [];
        $chartBorrows = [];
        
        $borrowHistory = \App\Models\Transaction::where('issue_date', '>=', Carbon::now()->subDays(30)->startOfDay())
            ->selectRaw('DATE(issue_date) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        for ($i = 30; $i >= 0; $i--) {
            $dateObj = Carbon::now()->subDays($i);
            $formattedDate = $dateObj->format('M d');
            $dateString = $dateObj->toDateString();
            
            $chartDates[] = $formattedDate;
            $chartBorrows[] = $borrowHistory[$dateString] ?? 0;
        }

        // Top Borrowed Books (Most borrowed)
        $topBooks = Book::withCount('transactions as borrow_records_count')
            ->orderBy('borrow_records_count', 'desc')
            ->take(5)
            ->get();

        // Activity Summary
        $activitySummary = [
            'new_members' => $newMembersThisMonth,
            'books_borrowed' => $borrowedThisMonth,
            'books_returned' => \App\Models\Transaction::where('status', 'Returned')
                ->whereMonth('return_date', Carbon::now()->month)
                ->whereYear('return_date', Carbon::now()->year)
                ->count(),
            'overdue_books' => $overdueThisMonth
        ];

        // Specific Report Data
        $reportType = $request->input('report_type', 'All Reports');
        $reportData = collect();

        if ($reportType === 'Borrowing History') {
            $reportData = \App\Models\Transaction::with(['book', 'bookCopy', 'member'])
                ->whereBetween('issue_date', [$startDate, $endDate])
                ->latest()
                ->get();
        } elseif ($reportType === 'Inventory Status') {
            $reportData = Book::with(['author', 'category', 'copies'])->get();
        } elseif ($reportType === 'Member Activity') {
            $reportData = Member::withCount(['transactions as borrowings_count' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('issue_date', [$startDate, $endDate]);
            }])
            ->withCount(['transactions as active_borrowings_count' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('issue_date', [$startDate, $endDate])
                  ->whereIn('status', ['Issued', 'Overdue']);
            }])
            ->withSum(['transactions as total_fines' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('issue_date', [$startDate, $endDate]);
            }], 'fine_amount')
            ->get();
        }

        return view('reports.index', compact(
            'totalBooks', 'totalMembers', 'booksBorrowed', 'overdueBooks', 'totalFines',
            'newBooksThisMonth', 'newMembersThisMonth', 'borrowedThisMonth', 'overdueThisMonth',
            'categoryLabels', 'categoryData', 'chartDates', 'chartBorrows',
            'topBooks', 'activitySummary', 'reportType', 'reportData', 'formattedRange'
        ));
    }

    public function export()
    {
        // Automatically calculate and update overdue records
        \App\Models\Transaction::where('status', 'Issued')
            ->where('due_date', '<', now())
            ->each(function ($transaction) {
                $transaction->update(['status' => 'Overdue']);
                if ($transaction->bookCopy) {
                    $transaction->bookCopy->update(['status' => 'Overdue']);
                }
            });

        $stats = [
            'total_books' => \App\Models\BookCopy::where('status', '!=', 'Retired')->count(),
            'total_members' => \App\Models\Member::count(),
            'active_borrowings' => \App\Models\Transaction::whereIn('status', ['Issued', 'Overdue'])->count(),
            'overdue_books' => \App\Models\Transaction::where('status', 'Overdue')->count(),
            'total_fines' => \App\Models\Transaction::sum('fine_amount'),
        ];
        
        $recent_borrowings = \App\Models\Transaction::with(['book', 'member'])
                                ->latest()
                                ->take(10)
                                ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf', compact('stats', 'recent_borrowings'));
        
        return $pdf->download('library-system-report.pdf');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Automatically calculate and update overdue records
        \App\Models\BorrowRecord::updateOverdueRecords();

        $totalBooks = \App\Models\Book::sum('total_copies');
        $totalMembers = \App\Models\Member::count();
        $borrowedBooks = \App\Models\BorrowRecord::where('status', 'borrowed')->count();
        $overdueBooks = \App\Models\BorrowRecord::where('status', 'overdue')->count();

        // Calculate total accumulated fines
        $totalFines = \App\Models\BorrowRecord::get()->sum('fine');

        $recentBorrowings = \App\Models\BorrowRecord::with(['member', 'book'])
            ->latest()
            ->take(5)
            ->get();

        $topBooks = \App\Models\Book::withCount('borrowRecords')
            ->orderByDesc('borrow_records_count')
            ->take(4)
            ->get();

        // Dynamic Chart Data (Last 15 days of borrowings)
        $chartData = [];
        $chartLabels = [];
        for ($i = 14; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subDays($i);
            $count = \App\Models\BorrowRecord::whereDate('borrow_date', $date->format('Y-m-d'))->count();
            $chartData[] = $count;
            $chartLabels[] = $date->format('M j');
        }

        // Dynamic Recent Activities
        $activities = collect();
        
        // 1. Recent Books
        $recentBooks = \App\Models\Book::latest()->take(5)->get()->map(function($book) {
            return (object) [
                'type' => 'book_added',
                'title' => 'New book added',
                'description' => $book->title . ' by ' . ($book->author->name ?? 'Unknown'),
                'created_at' => $book->created_at,
                'color' => 'indigo',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>'
            ];
        });
        
        // 2. Recent Members
        $recentMembers = \App\Models\Member::latest()->take(5)->get()->map(function($member) {
            return (object) [
                'type' => 'member_added',
                'title' => 'New member registered',
                'description' => $member->name,
                'created_at' => $member->created_at,
                'color' => 'blue',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>'
            ];
        });

        // 3. Recent Borrowings
        $recentIssues = \App\Models\BorrowRecord::with(['member', 'book'])->latest()->take(5)->get()->map(function($record) {
            $isReturn = $record->status === 'returned';
            return (object) [
                'type' => $isReturn ? 'book_returned' : 'book_issued',
                'title' => $isReturn ? 'Book returned' : 'Book issued',
                'description' => $record->book->title . ' to ' . explode(' ', $record->member->name)[0],
                'created_at' => $record->created_at,
                'color' => $isReturn ? 'orange' : 'green',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>'
            ];
        });

        $activities = $activities->concat($recentBooks)->concat($recentMembers)->concat($recentIssues)
            ->sortByDesc('created_at')
            ->take(5)
            ->values();

        return view('dashboard', compact(
            'totalBooks', 
            'totalMembers', 
            'borrowedBooks', 
            'overdueBooks', 
            'totalFines',
            'recentBorrowings', 
            'topBooks',
            'chartData',
            'chartLabels',
            'activities'
        ));
    }
}

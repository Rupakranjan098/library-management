<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Member;
use App\Models\BorrowRecord;
use Illuminate\Http\Request;

class PublicCatalogController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('books')->orderBy('name')->get();
        $members = Member::orderBy('name')->get();

        // Calculate dynamic stats
        $stats = [
            'total_books' => Book::sum('total_copies') ?? 0,
            'total_members' => Member::count(),
            'books_borrowed' => BorrowRecord::where('status', 'borrowed')->count(),
            'upcoming_events' => 28, // Mocked event count matching mockup design
        ];

        $query = Book::with(['author', 'category']);

        // Search text filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%")
                  ->orWhereHas('author', function ($aq) use ($search) {
                      $aq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Category filter
        if ($request->filled('category_id') && $request->input('category_id') !== 'all') {
            $query->where('category_id', $request->input('category_id'));
        }

        $books = $query->latest()->get();

        return view('welcome', compact('categories', 'books', 'members', 'stats'));
    }

    public function reserve(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $request->validate([
            'member_id' => 'required|exists:members,id',
        ]);

        if ($book->available_copies <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'This book is currently out of stock.'
            ], 422);
        }

        // Check if member already has an active borrow record for this book
        $hasBorrowed = BorrowRecord::where('book_id', $book->id)
            ->where('member_id', $request->member_id)
            ->whereNull('return_date')
            ->exists();

        if ($hasBorrowed) {
            return response()->json([
                'success' => false,
                'message' => 'This member has already borrowed/reserved this book and not returned it yet.'
            ], 422);
        }

        // Create the borrow record
        BorrowRecord::create([
            'book_id' => $book->id,
            'member_id' => $request->member_id,
            'borrow_date' => now()->toDateString(),
            'due_date' => now()->addDays(14)->toDateString(),
            'status' => 'borrowed',
        ]);

        // Decrement copies
        $book->decrement('available_copies');

        return response()->json([
            'success' => true,
            'message' => 'Book reserved successfully!',
            'available_copies' => $book->available_copies,
            'total_copies' => $book->total_copies,
        ]);
    }
}

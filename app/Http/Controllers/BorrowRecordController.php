<?php

namespace App\Http\Controllers;

use App\Models\BorrowRecord;
use Illuminate\Http\Request;

class BorrowRecordController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\BorrowRecord::with(['book', 'member']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('book', function ($bq) use ($search) {
                    $bq->where('title', 'like', "%{$search}%")
                       ->orWhere('isbn', 'like', "%{$search}%");
                })->orWhereHas('member', function ($mq) use ($search) {
                    $mq->where('name', 'like', "%{$search}%");
                })->orWhere('status', 'like', "%{$search}%");
            });
        }

        $borrowings = $query->latest()->get();
        return view('borrowings.index', compact('borrowings'));
    }

    public function create()
    {
        return view('borrowings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'member_id' => 'required|exists:members,id',
            'borrow_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:borrow_date',
            'status' => 'required|in:borrowed,returned,overdue',
        ]);

        $book = \App\Models\Book::findOrFail($validated['book_id']);

        if (in_array($validated['status'], ['borrowed', 'overdue'])) {
            if ($book->available_copies <= 0) {
                return back()->withErrors(['book_id' => 'This book is currently out of stock.'])->withInput();
            }
            $book->decrement('available_copies');
        }

        BorrowRecord::create($validated);

        return redirect()->route('borrowings.index')->with('success', 'Borrowing record created successfully!');
    }

    public function show($id)
    {
        $borrowRecord = BorrowRecord::with(['book', 'member'])->findOrFail($id);
        return view('borrowings.show', compact('borrowRecord'));
    }

    public function edit($id)
    {
        $borrowing = BorrowRecord::findOrFail($id);
        return view('borrowings.edit', compact('borrowing'));
    }

    public function update(Request $request, $id)
    {
        $borrowRecord = BorrowRecord::findOrFail($id);
        
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'member_id' => 'required|exists:members,id',
            'borrow_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:borrow_date',
            'status' => 'required|in:borrowed,returned,overdue',
        ]);

        $oldStatus = $borrowRecord->status;
        $newStatus = $validated['status'];
        $oldBookId = $borrowRecord->book_id;
        $newBookId = $validated['book_id'];

        if ($oldBookId != $newBookId) {
            $oldBook = \App\Models\Book::find($oldBookId);
            $newBook = \App\Models\Book::findOrFail($newBookId);

            if (in_array($oldStatus, ['borrowed', 'overdue'])) {
                if ($oldBook) {
                    $oldBook->increment('available_copies');
                }
            }

            if (in_array($newStatus, ['borrowed', 'overdue'])) {
                if ($newBook->available_copies <= 0) {
                    return back()->withErrors(['book_id' => 'The newly selected book is currently out of stock.'])->withInput();
                }
                $newBook->decrement('available_copies');
            }
        } else {
            $book = \App\Models\Book::findOrFail($newBookId);
            if (in_array($oldStatus, ['borrowed', 'overdue']) && $newStatus === 'returned') {
                $book->increment('available_copies');
            } elseif ($oldStatus === 'returned' && in_array($newStatus, ['borrowed', 'overdue'])) {
                if ($book->available_copies <= 0) {
                    return back()->withErrors(['status' => 'No copies of this book are available to be borrowed.'])->withInput();
                }
                $book->decrement('available_copies');
            }
        }

        $borrowRecord->update($validated);

        return redirect()->route('borrowings.index')->with('success', 'Borrowing record updated successfully!');
    }

    public function destroy($id)
    {
        $borrowRecord = BorrowRecord::findOrFail($id);
        
        if (in_array($borrowRecord->status, ['borrowed', 'overdue'])) {
            $book = \App\Models\Book::find($borrowRecord->book_id);
            if ($book) {
                $book->increment('available_copies');
            }
        }

        $borrowRecord->delete();

        return redirect()->route('borrowings.index')->with('success', 'Borrowing record deleted successfully!');
    }
}

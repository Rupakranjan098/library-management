<?php

namespace App\Http\Controllers;

use App\Models\BorrowRecord;
use App\Models\BookCopy;
use App\Models\Book;
use Illuminate\Http\Request;

class BorrowRecordController extends Controller
{
    public function index(Request $request)
    {
        // Automatically calculate and update overdue records
        \App\Models\BorrowRecord::updateOverdueRecords();

        if ($request->filled('search')) {
            $search = $request->input('search');
            if (str_starts_with(strtoupper($search), 'LIB-')) {
                return redirect()->route('books.index', ['search' => $search]);
            }
        }

        $query = \App\Models\BorrowRecord::with(['book', 'member']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('book', function ($bq) use ($search) {
                    $bq->where('title', 'like', "%{$search}%")
                       ->orWhereHas('copies', function ($cq) use ($search) {
                           $cq->where('barcode_id', 'like', "%{$search}%");
                       });
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
        if ($request->filled('barcode_id')) {
            $copy = BookCopy::where('barcode_id', $request->input('barcode_id'))->first();
            $book = $copy ? $copy->book : null;
            if ($book) {
                $request->merge(['book_id' => $book->id]);
            } else {
                return back()->withErrors(['barcode_id' => 'No book found with the scanned barcode ID.'])->withInput();
            }
        }

        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'member_name' => 'required|string|max:255',
            'borrow_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:borrow_date',
            'status' => 'required|in:borrowed,returned,overdue',
        ]);

        $memberName = $validated['member_name'];
        $member = \App\Models\Member::where('name', $memberName)->first();
        if (!$member) {
            $email = strtolower(str_replace(' ', '.', $memberName)) . '-' . rand(100, 999) . '@example.com';
            $member = \App\Models\Member::create([
                'name' => $memberName,
                'email' => $email,
                'phone' => '0000000000',
                'membership_expiry' => now()->addYear()->toDateString(),
            ]);
        }
        $validated['member_id'] = $member->id;
        unset($validated['member_name']);

        $book = \App\Models\Book::findOrFail($validated['book_id']);

        if (in_array($validated['status'], ['borrowed', 'overdue'])) {
            $availableCopies = $book->copies()->where('status', 'Available')->count();
            if ($availableCopies <= 0) {
                return back()->withErrors(['book_id' => 'This book is currently out of stock.'])->withInput();
            }
            $copy = $book->copies()->where('status', 'Available')->first();
            if ($copy) {
                $copy->update(['status' => 'Issued']);
            }
        }

        if ($validated['status'] === 'returned') {
            $validated['return_date'] = now()->toDateString();
        } else {
            $validated['return_date'] = null;
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
        
        if ($request->filled('barcode_id')) {
            $copy = BookCopy::where('barcode_id', $request->input('barcode_id'))->first();
            $book = $copy ? $copy->book : null;
            if ($book) {
                $request->merge(['book_id' => $book->id]);
            } else {
                return back()->withErrors(['barcode_id' => 'No book found with the scanned barcode ID.'])->withInput();
            }
        }

        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'member_name' => 'required|string|max:255',
            'borrow_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:borrow_date',
            'status' => 'required|in:borrowed,returned,overdue',
        ]);

        $memberName = $validated['member_name'];
        $member = \App\Models\Member::where('name', $memberName)->first();
        if (!$member) {
            $email = strtolower(str_replace(' ', '.', $memberName)) . '-' . rand(100, 999) . '@example.com';
            $member = \App\Models\Member::create([
                'name' => $memberName,
                'email' => $email,
                'phone' => '0000000000',
                'membership_expiry' => now()->addYear()->toDateString(),
            ]);
        }
        $validated['member_id'] = $member->id;
        unset($validated['member_name']);

        $oldStatus = $borrowRecord->status;
        $newStatus = $validated['status'];
        $oldBookId = $borrowRecord->book_id;
        $newBookId = $validated['book_id'];

        if ($oldBookId != $newBookId) {
            $oldBook = \App\Models\Book::find($oldBookId);
            $newBook = \App\Models\Book::findOrFail($newBookId);

            if (in_array($oldStatus, ['borrowed', 'overdue'])) {
                if ($oldBook) {
                    $copy = $oldBook->copies()->where('status', 'Issued')->first();
                    if ($copy) {
                        $copy->update(['status' => 'Available']);
                    }
                }
            }

            if (in_array($newStatus, ['borrowed', 'overdue'])) {
                $availableCopies = $newBook->copies()->where('status', 'Available')->count();
                if ($availableCopies <= 0) {
                    return back()->withErrors(['book_id' => 'The newly selected book is currently out of stock.'])->withInput();
                }
                $copy = $newBook->copies()->where('status', 'Available')->first();
                if ($copy) {
                    $copy->update(['status' => 'Issued']);
                }
            }
        } else {
            $book = \App\Models\Book::findOrFail($newBookId);
            if (in_array($oldStatus, ['borrowed', 'overdue']) && $newStatus === 'returned') {
                $copy = $book->copies()->where('status', 'Issued')->first();
                if ($copy) {
                    $copy->update(['status' => 'Available']);
                }
            } elseif ($oldStatus === 'returned' && in_array($newStatus, ['borrowed', 'overdue'])) {
                $availableCopies = $book->copies()->where('status', 'Available')->count();
                if ($availableCopies <= 0) {
                    return back()->withErrors(['status' => 'No copies of this book are available to be borrowed.'])->withInput();
                }
                $copy = $book->copies()->where('status', 'Available')->first();
                if ($copy) {
                    $copy->update(['status' => 'Issued']);
                }
            }
        }

        if ($newStatus === 'returned') {
            if (!$borrowRecord->return_date) {
                $validated['return_date'] = now()->toDateString();
            } else {
                $validated['return_date'] = $borrowRecord->return_date;
            }
        } else {
            $validated['return_date'] = null;
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
                $copy = $book->copies()->where('status', 'Issued')->first();
                if ($copy) {
                    $copy->update(['status' => 'Available']);
                }
            }
        }

        $borrowRecord->delete();

        return redirect()->route('borrowings.index')->with('success', 'Borrowing record deleted successfully!');
    }
}

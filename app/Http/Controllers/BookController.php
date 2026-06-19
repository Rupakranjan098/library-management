<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Book::with(['author', 'category']);

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

        $books = $query->latest()->get();
        return view('books.index', compact('books'));
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books',
            'category_id' => 'required|exists:categories,id',
            'author_id' => 'required|exists:authors,id',
            'total_copies' => 'required|integer|min:1',
        ]);

        // When a new book is created, all copies are available.
        $validated['available_copies'] = $validated['total_copies'];

        Book::create($validated);

        return redirect()->route('books.index')->with('success', 'Book added successfully!');
    }

    public function show(Book $book)
    {
        $book->load(['category', 'author', 'borrowRecords']);
        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn,' . $book->id,
            'category_id' => 'required|exists:categories,id',
            'author_id' => 'required|exists:authors,id',
            'total_copies' => 'required|integer|min:1',
        ]);

        // Calculate new available copies based on the difference
        $difference = $validated['total_copies'] - $book->total_copies;
        $validated['available_copies'] = $book->available_copies + $difference;

        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Book updated successfully!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();

        $importedCount = 0;
        $skippedCount = 0;
        $skips = [];

        if (($handle = fopen($path, 'r')) !== false) {
            // Get headers
            $headers = fgetcsv($handle, 1000, ',');
            if ($headers) {
                // Normalize headers (trim, lowercase)
                $headers = array_map(function($header) {
                    return strtolower(trim($header));
                }, $headers);

                // Map header names to their corresponding column indices
                $titleIdx = array_search('title', $headers);
                $isbnIdx = array_search('isbn', $headers);
                $authorIdx = array_search('author', $headers);
                if ($authorIdx === false) {
                    $authorIdx = array_search('author_name', $headers);
                }
                $categoryIdx = array_search('category', $headers);
                if ($categoryIdx === false) {
                    $categoryIdx = array_search('category_name', $headers);
                }
                $copiesIdx = array_search('total_copies', $headers);
                if ($copiesIdx === false) {
                    $copiesIdx = array_search('copies', $headers);
                }

                if ($titleIdx === false || $isbnIdx === false || $authorIdx === false || $categoryIdx === false) {
                    fclose($handle);
                    return back()->withErrors(['csv_file' => 'CSV must contain headers: title, isbn, author (or author_name), category (or category_name).']);
                }

                $rowNum = 1;
                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    $rowNum++;
                    
                    // Skip empty rows
                    if (empty(array_filter($data))) {
                        continue;
                    }

                    $title = isset($data[$titleIdx]) ? trim($data[$titleIdx]) : '';
                    $isbn = isset($data[$isbnIdx]) ? trim($data[$isbnIdx]) : '';
                    $authorName = isset($data[$authorIdx]) ? trim($data[$authorIdx]) : '';
                    $categoryName = isset($data[$categoryIdx]) ? trim($data[$categoryIdx]) : '';
                    $totalCopies = isset($data[$copiesIdx]) ? intval(trim($data[$copiesIdx])) : 1;

                    if ($totalCopies < 1) {
                        $totalCopies = 1;
                    }

                    if (empty($title) || empty($isbn) || empty($authorName) || empty($categoryName)) {
                        $skippedCount++;
                        $skips[] = "Row {$rowNum}: Missing required fields (title, isbn, author, or category).";
                        continue;
                    }

                    // Check duplicate isbn in the database
                    if (Book::where('isbn', $isbn)->exists()) {
                        $skippedCount++;
                        $skips[] = "Row {$rowNum}: ISBN '{$isbn}' already exists.";
                        continue;
                    }

                    // Find or create Author
                    $author = \App\Models\Author::where('name', $authorName)->first();
                    if (!$author) {
                        $author = \App\Models\Author::create([
                            'name' => $authorName,
                            'bio' => 'Added via CSV import.'
                        ]);
                    }

                    // Find or create Category
                    $category = \App\Models\Category::where('name', $categoryName)->first();
                    if (!$category) {
                        $category = \App\Models\Category::create([
                            'name' => $categoryName,
                            'description' => 'Added via CSV import.'
                        ]);
                    }

                    // Create book
                    Book::create([
                        'title' => $title,
                        'isbn' => $isbn,
                        'author_id' => $author->id,
                        'category_id' => $category->id,
                        'total_copies' => $totalCopies,
                        'available_copies' => $totalCopies,
                    ]);

                    $importedCount++;
                }
            }
            fclose($handle);
        }

        $message = "Imported {$importedCount} books successfully.";
        if ($skippedCount > 0) {
            $message .= " Skipped {$skippedCount} rows.";
            return redirect()->route('books.index')->with('success', $message)->withErrors($skips);
        }

        return redirect()->route('books.index')->with('success', $message);
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Book deleted successfully!');
    }
}

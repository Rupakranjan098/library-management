<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->filled('search')) {
            $search = $request->input('search');
            $cleanSearch = str_replace(['-', ' '], '', $search);
            if (preg_match('/^(?:\d{9}[\dX]|\d{13})$/i', $cleanSearch) || (ctype_digit($cleanSearch) && strlen($cleanSearch) >= 8)) {
                return redirect()->route('books.index', ['search' => $search]);
            }
        }

        $query = \App\Models\Author::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('bio', 'like', "%{$search}%");
        }

        $authors = $query->latest()->get();
        return view('authors.index', compact('authors'));
    }

    public function create()
    {
        return view('authors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
        ]);

        Author::create($validated);

        return redirect()->route('authors.index')->with('success', 'Author created successfully!');
    }

    public function show(Author $author)
    {
        return view('authors.show', compact('author'));
    }

    public function edit(Author $author)
    {
        return view('authors.edit', compact('author'));
    }

    public function update(Request $request, Author $author)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
        ]);

        $author->update($validated);

        return redirect()->route('authors.index')->with('success', 'Author updated successfully!');
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
                $nameIdx = array_search('name', $headers);
                if ($nameIdx === false) {
                    $nameIdx = array_search('author_name', $headers);
                }
                $bioIdx = array_search('bio', $headers);
                if ($bioIdx === false) {
                    $bioIdx = array_search('biography', $headers);
                }

                if ($nameIdx === false) {
                    fclose($handle);
                    return back()->withErrors(['csv_file' => 'CSV must contain at least a "name" header (or "author_name").']);
                }

                $rowNum = 1;
                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    $rowNum++;
                    
                    // Skip empty rows
                    if (empty(array_filter($data))) {
                        continue;
                    }

                    $name = isset($data[$nameIdx]) ? trim($data[$nameIdx]) : '';
                    $bio = ($bioIdx !== false && isset($data[$bioIdx])) ? trim($data[$bioIdx]) : '';

                    if (empty($name)) {
                        $skippedCount++;
                        $skips[] = "Row {$rowNum}: Author name is empty.";
                        continue;
                    }

                    // Check duplicate author name in the database
                    if (Author::where('name', $name)->exists()) {
                        $skippedCount++;
                        $skips[] = "Row {$rowNum}: Author '{$name}' already exists.";
                        continue;
                    }

                    // Create Author
                    Author::create([
                        'name' => $name,
                        'bio' => $bio ?: 'Added via CSV import.',
                    ]);

                    $importedCount++;
                }
            }
            fclose($handle);
        }

        $message = "Imported {$importedCount} authors successfully.";
        if ($skippedCount > 0) {
            $message .= " Skipped {$skippedCount} rows.";
            return redirect()->route('authors.index')->with('success', $message)->withErrors($skips);
        }

        return redirect()->route('authors.index')->with('success', $message);
    }

    public function destroy(Author $author)
    {
        $author->delete();

        return redirect()->route('authors.index')->with('success', 'Author deleted successfully!');
    }
}

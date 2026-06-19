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

    public function destroy(Author $author)
    {
        $author->delete();

        return redirect()->route('authors.index')->with('success', 'Author deleted successfully!');
    }
}

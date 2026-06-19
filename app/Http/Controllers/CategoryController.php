<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
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

        $query = \App\Models\Category::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $categories = $query->latest()->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Category created successfully!');
    }

    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
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
                    $nameIdx = array_search('category_name', $headers);
                }
                $descIdx = array_search('description', $headers);

                if ($nameIdx === false) {
                    fclose($handle);
                    return back()->withErrors(['csv_file' => 'CSV must contain at least a "name" header (or "category_name").']);
                }

                $rowNum = 1;
                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    $rowNum++;
                    
                    // Skip empty rows
                    if (empty(array_filter($data))) {
                        continue;
                    }

                    $name = isset($data[$nameIdx]) ? trim($data[$nameIdx]) : '';
                    $description = ($descIdx !== false && isset($data[$descIdx])) ? trim($data[$descIdx]) : '';

                    if (empty($name)) {
                        $skippedCount++;
                        $skips[] = "Row {$rowNum}: Category name is empty.";
                        continue;
                    }

                    // Check duplicate category name in the database
                    if (Category::where('name', $name)->exists()) {
                        $skippedCount++;
                        $skips[] = "Row {$rowNum}: Category '{$name}' already exists.";
                        continue;
                    }

                    // Create Category
                    Category::create([
                        'name' => $name,
                        'description' => $description ?: 'Added via CSV import.',
                    ]);

                    $importedCount++;
                }
            }
            fclose($handle);
        }

        $message = "Imported {$importedCount} categories successfully.";
        if ($skippedCount > 0) {
            $message .= " Skipped {$skippedCount} rows.";
            return redirect()->route('categories.index')->with('success', $message)->withErrors($skips);
        }

        return redirect()->route('categories.index')->with('success', $message);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');
    }
}

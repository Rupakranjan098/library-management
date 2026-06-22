<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCopy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with(['author', 'category', 'copies']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('copies', function ($cq) use ($search) {
                      $cq->where('barcode_id', 'like', "%{$search}%");
                  })
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
        $books = Book::orderBy('title')->get();
        return view('books.create', compact('books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_mode' => 'required|in:new,existing',
            'barcode_id' => 'required|string',
        ]);

        $barcodeId = $request->input('barcode_id');
        $copy = BookCopy::where('barcode_id', $barcodeId)->first();

        if (!$copy || $copy->status !== 'Unassigned') {
            return back()->withErrors([
                'barcode_id' => 'This barcode is invalid or already assigned to another book. Please scan an unassigned barcode sticker.'
            ])->withInput();
        }

        if ($request->input('book_mode') === 'existing') {
            $validated = $request->validate([
                'book_id' => 'required|exists:books,id',
            ]);
            $bookId = $validated['book_id'];
            $book = Book::findOrFail($bookId);
        } else {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'isbn' => 'nullable|string',
                'category_id' => 'required|exists:categories,id',
                'author_id' => 'required|exists:authors,id',
            ]);

            // Check if Book already exists by title and author
            $book = Book::firstOrCreate(
                [
                    'title' => $validated['title'],
                    'author_id' => $validated['author_id'],
                ],
                [
                    'isbn' => $validated['isbn'],
                    'category_id' => $validated['category_id'],
                ]
            );
        }

        DB::transaction(function () use ($copy, $book) {
            $copy->update([
                'book_id' => $book->id,
                'status' => 'Available',
                'assigned_at' => now(),
            ]);
        });

        return redirect()->route('books.show', $book->id)->with('success', 'Barcode linked to book copy successfully!');
    }

    public function show(Book $book)
    {
        $book->load(['category', 'author', 'copies.transactions.member', 'transactions.member']);
        return view('books.show', compact('book'));
    }

    public function printBarcodes(Request $request)
    {
        $query = BookCopy::with(['book.author']);

        if ($request->filled('ids')) {
            $ids = $request->input('ids');
            if (is_string($ids)) {
                $ids = explode(',', $ids);
            }
            if ($request->boolean('by_book')) {
                $query->whereIn('book_id', $ids);
            } else {
                $query->whereIn('id', $ids);
            }
        } elseif ($request->boolean('unprinted')) {
            $query->whereNull('barcode_printed_at');
        } elseif ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('barcode_id', 'like', "%{$search}%")
                  ->orWhereHas('book', function ($bq) use ($search) {
                      $bq->where('title', 'like', "%{$search}%")
                        ->orWhereHas('author', function ($aq) use ($search) {
                            $aq->where('name', 'like', "%{$search}%");
                        });
                  });
            });
        }

        // We name the variable $books for view compatibility, but it holds BookCopy records
        $books = $query->latest()->paginate(100)->withQueryString();

        $cols = (int) $request->input('cols', 4);
        $height = (int) $request->input('height', 100);
        $fontSize = (int) $request->input('font_size', 10);

        if ($request->input('download') === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('books.print-barcodes-pdf', compact('books', 'cols', 'height', 'fontSize'));
            return $pdf->download('barcodes.pdf');
        }

        return view('books.print-barcodes', compact('books', 'cols', 'height', 'fontSize'));
    }

    public function markPrinted(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:book_copies,id',
        ]);

        BookCopy::whereIn('id', $validated['ids'])->update([
            'barcode_printed_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    public function showGenerateBarcodesForm()
    {
        return view('books.generate-barcodes');
    }

    public function lookup(Request $request)
    {
        $barcodeId = $request->input('barcode_id');
        $copy = null;
        $history = collect();

        if ($barcodeId) {
            $copy = BookCopy::with(['book.author', 'book.category'])
                ->where('barcode_id', $barcodeId)
                ->first();

            if ($copy) {
                $history = \App\Models\Transaction::with('member')
                    ->where('book_copy_id', $copy->id)
                    ->latest()
                    ->get();
            } else {
                session()->flash('error_lookup', 'Barcode not found in the system records. Please scan or check the value.');
            }
        }

        return view('books.lookup', compact('copy', 'history', 'barcodeId'));
    }

    public function generateBarcodes(Request $request)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:500',
        ]);

        $quantity = $validated['quantity'];
        $generatedIds = [];

        DB::transaction(function () use ($quantity, &$generatedIds) {
            for ($i = 0; $i < $quantity; $i++) {
                $copy = BookCopy::create([
                    'book_id' => null,
                    'status' => 'Unassigned',
                ]);

                $generatedIds[] = $copy->id;
            }
        });

        return redirect()->route('books.print-barcodes', [
            'ids' => implode(',', $generatedIds),
        ])->with('success', "Successfully generated {$quantity} unassigned barcodes!");
    }

    public function linkBarcode(Request $request, Book $book)
    {
        $validated = $request->validate([
            'barcode_id' => 'required|string',
        ]);

        $barcodeId = $validated['barcode_id'];
        $copy = BookCopy::where('barcode_id', $barcodeId)->first();

        if (!$copy || $copy->status !== 'Unassigned') {
            return response()->json([
                'success' => false,
                'message' => 'This barcode is invalid or already assigned to another book. Please scan an unassigned barcode sticker.'
            ], 422);
        }

        DB::transaction(function () use ($copy, $book) {
            $copy->update([
                'book_id' => $book->id,
                'status' => 'Available',
                'assigned_at' => now(),
            ]);
        });

        return response()->json([
            'success' => true,
            'barcode_id' => $copy->barcode_id,
            'total_copies' => $book->copies()->where('status', '!=', 'Retired')->count(),
            'available_copies' => $book->copies()->where('status', 'Available')->count(),
        ]);
    }

    public function generateParticularCopy(Book $book)
    {
        $copy = DB::transaction(function () use ($book) {
            return BookCopy::create([
                'book_id' => $book->id,
                'status' => 'Available',
                'assigned_at' => now(),
            ]);
        });

        return redirect()->route('books.print-barcodes', [
            'ids' => $copy->id,
        ])->with('success', "Successfully generated barcode {$copy->barcode_id} for '{$book->title}'!");
    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'author_id' => 'required|exists:authors,id',
            'total_copies' => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($validated, $book) {
            $totalCopies = (int) $validated['total_copies'];
            unset($validated['total_copies']);

            $book->update($validated);

            // Sync copy count
            $currentCopiesCount = $book->copies()->where('status', '!=', 'Retired')->count();
            $difference = $totalCopies - $currentCopiesCount;

            if ($difference > 0) {
                for ($i = 0; $i < $difference; $i++) {
                    BookCopy::create([
                        'book_id' => $book->id,
                        'status' => 'Available',
                        'assigned_at' => now(),
                    ]);
                }
            } elseif ($difference < 0) {
                // Safely retire only available copies to avoid breaking issued copy logs
                $copiesToRetire = $book->copies()
                    ->where('status', 'Available')
                    ->limit(abs($difference))
                    ->get();

                foreach ($copiesToRetire as $copy) {
                    $copy->update(['status' => 'Retired']);
                }
            }

            return redirect()->route('books.index')->with('success', 'Book updated successfully!');
        });
    }

    public function template()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="books_import_template.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['title', 'barcode', 'author', 'category', 'total_copies']);
            fputcsv($file, ['The Hobbit', 'LIB-000101', 'J.R.R. Tolkien', 'Fantasy', '5']);
            fputcsv($file, ['Foundation', 'LIB-000102', 'Isaac Asimov', 'Science Fiction', '10']);
            fputcsv($file, ['Cosmos', 'LIB-000103', 'Carl Sagan', 'Non-Fiction', '3']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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
            $headers = fgetcsv($handle, 1000, ',');
            if ($headers) {
                $headers = array_map(function($header) {
                    return strtolower(trim($header));
                }, $headers);

                $titleIdx = array_search('title', $headers);
                $barcodeIdx = array_search('barcode', $headers);
                if ($barcodeIdx === false) {
                    $barcodeIdx = array_search('barcode_id', $headers);
                }
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

                if ($titleIdx === false || ($barcodeIdx === false && $isbnIdx === false) || $authorIdx === false || $categoryIdx === false) {
                    fclose($handle);
                    return back()->withErrors(['csv_file' => 'CSV must contain headers: title, barcode (or isbn), author, category.']);
                }

                $rowNum = 1;
                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    $rowNum++;
                    
                    if (empty(array_filter($data))) {
                        continue;
                    }

                    $title = isset($data[$titleIdx]) ? trim($data[$titleIdx]) : '';
                    
                    $barcode = '';
                    if ($barcodeIdx !== false && isset($data[$barcodeIdx])) {
                        $barcode = trim($data[$barcodeIdx]);
                    } elseif ($isbnIdx !== false && isset($data[$isbnIdx])) {
                        $barcode = trim($data[$isbnIdx]);
                    }

                    $authorName = isset($data[$authorIdx]) ? trim($data[$authorIdx]) : '';
                    $categoryName = isset($data[$categoryIdx]) ? trim($data[$categoryIdx]) : '';
                    $totalCopies = isset($data[$copiesIdx]) ? intval(trim($data[$copiesIdx])) : 1;

                    if ($totalCopies < 1) {
                        $totalCopies = 1;
                    }

                    if (empty($title) || empty($authorName) || empty($categoryName)) {
                        $skippedCount++;
                        $skips[] = "Row {$rowNum}: Missing required fields.";
                        continue;
                    }

                    if (!empty($barcode) && (\App\Models\Book::where('isbn', $barcode)->exists() || BookCopy::where('barcode_id', $barcode)->exists())) {
                        $skippedCount++;
                        $skips[] = "Row {$rowNum}: Barcode/ISBN '{$barcode}' already exists.";
                        continue;
                    }

                    $author = \App\Models\Author::where('name', $authorName)->first();
                    if (!$author) {
                        $author = \App\Models\Author::create([
                            'name' => $authorName,
                            'bio' => 'Added via CSV import.'
                        ]);
                    }

                    $category = \App\Models\Category::where('name', $categoryName)->first();
                    if (!$category) {
                        $category = \App\Models\Category::create([
                            'name' => $categoryName,
                            'description' => 'Added via CSV import.'
                        ]);
                    }

                    DB::transaction(function () use ($title, $barcode, $author, $category, $totalCopies) {
                        $book = Book::firstOrCreate(
                            [
                                'title' => $title,
                                'author_id' => $author->id,
                            ],
                            [
                                'isbn' => $barcode,
                                'category_id' => $category->id,
                            ]
                        );

                        for ($i = 0; $i < $totalCopies; $i++) {
                            $copyData = [
                                'book_id' => $book->id,
                                'status' => 'Available',
                                'assigned_at' => now(),
                            ];
                            if ($i === 0 && !empty($barcode)) {
                                $copyData['barcode_id'] = $barcode;
                            }
                            BookCopy::create($copyData);
                        }
                    });

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

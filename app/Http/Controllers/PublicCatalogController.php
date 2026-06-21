<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Member;
use App\Models\BorrowRecord;
use App\Models\Setting;
use Illuminate\Http\Request;

class PublicCatalogController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('books')->orderBy('name')->get();
        $members = Member::orderBy('name')->get();

        // Real dynamic stats from actual database
        $stats = [
            'total_books'      => Book::count(),
            'total_copies'     => \App\Models\BookCopy::where('status', '!=', 'Retired')->count(),
            'total_members'    => Member::count(),
            'books_borrowed'   => \App\Models\BookCopy::where('status', 'Issued')->count(),
            'total_authors'    => \App\Models\Author::count(),
            'total_categories' => $categories->count(),
        ];

        $query = Book::with(['author', 'category', 'copies']);

        // Search text filter
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

        // Category filter
        if ($request->filled('category_id') && $request->input('category_id') !== 'all') {
            $query->where('category_id', $request->input('category_id'));
        }

        $books = $query->latest()->get();

        // New arrivals - latest 4 books regardless of filters
        $newArrivals = Book::with(['author', 'category'])->latest()->take(4)->get();

        // All site/contact settings from DB - fully dynamic
        $site = [
            'library_name'         => Setting::get('library_name', 'Public Library'),
            'library_tagline'      => Setting::get('library_tagline', 'Knowledge for Everyone'),
            'hero_title'           => Setting::get('hero_title', 'Discover Your Next Great Read'),
            'hero_subtitle'        => Setting::get('hero_subtitle', 'Explore thousands of books across all genres. Find knowledge, inspiration, and adventure.'),
            'about_text'           => Setting::get('about_text', 'We believe knowledge should be accessible to all. Our library has been serving the community for decades.'),
            'contact_address'      => Setting::get('contact_address', '123 Library Lane, New Delhi - 110001, India'),
            'contact_phone'        => Setting::get('contact_phone', '+91 98765 43210'),
            'contact_phone_hours'  => Setting::get('contact_phone_hours', 'Mon-Sat, 9 AM - 6 PM'),
            'contact_email'        => Setting::get('contact_email', 'info@publiclibrary.in'),
            'contact_email_note'   => Setting::get('contact_email_note', 'We reply within 24 hours'),
            'hours_weekday'        => Setting::get('hours_weekday', 'Mon - Fri: 8 AM - 8 PM'),
            'hours_saturday'       => Setting::get('hours_saturday', 'Saturday: 9 AM - 5 PM'),
            'hours_sunday'         => Setting::get('hours_sunday', 'Sunday: Closed'),
        ];

        return view('welcome', compact('categories', 'books', 'members', 'stats', 'newArrivals', 'site'));
    }

    public function reserve(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $request->validate([
            'member_id' => 'required|exists:members,id',
        ]);

        $availableCopies = $book->copies()->where('status', 'Available')->count();
        if ($availableCopies <= 0) {
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

        // Find an available copy to issue
        $copy = $book->copies()->where('status', 'Available')->first();

        // Create the borrow record
        BorrowRecord::create([
            'book_id'     => $book->id,
            'member_id'   => $request->member_id,
            'borrow_date' => now()->toDateString(),
            'due_date'    => now()->addDays((int) Setting::get('borrow_duration', 15))->toDateString(),
            'status'      => 'borrowed',
        ]);

        // Decrement copies status
        $copy->update(['status' => 'Issued']);

        return response()->json([
            'success'          => true,
            'message'          => 'Book reserved successfully!',
            'available_copies' => $book->copies()->where('status', 'Available')->count(),
            'total_copies'     => $book->copies()->where('status', '!=', 'Retired')->count(),
        ]);
    }

    public function contact(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:2000',
        ]);

        // Log the contact message for admin visibility
        \Illuminate\Support\Facades\Log::info('Contact Form Submission', $validated);

        $libraryName = Setting::get('library_name', 'the library');
        return response()->json([
            'success' => true,
            'message' => "Thank you {$validated['name']}! Your message has been received by {$libraryName}. We'll get back to you within 1-2 business days.",
        ]);
    }
}

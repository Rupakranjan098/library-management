@extends('layouts.admin')

@section('title', 'Add Book Copy - Library Management')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Add Book Copy</h1>
            <p class="text-slate-400 mt-1 text-sm">Register a new title or add a physical copy by linking an unassigned barcode.</p>
        </div>
        <a href="{{ route('books.index') }}" class="text-slate-400 hover:text-white transition-colors flex items-center bg-slate-800/50 hover:bg-slate-700/50 px-4 py-2 rounded-xl border border-slate-700">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Books
        </a>
    </div>

    <div class="dark-card rounded-3xl p-8 max-w-3xl">
        <form action="{{ route('books.store') }}" method="POST">
            @csrf
            
            <!-- Book Mode Selection -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-slate-300 mb-3">Book Catalog Option</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="relative flex flex-col p-4 bg-slate-800/40 border border-slate-700 rounded-2xl cursor-pointer hover:border-slate-600 transition-all select-none">
                        <input type="radio" name="book_mode" value="new" checked onclick="toggleBookMode('new')" class="absolute top-4 right-4 text-indigo-600 focus:ring-indigo-500 h-4 w-4 border-slate-700 bg-slate-800">
                        <span class="block text-sm font-bold text-white mb-1">Register a New Title</span>
                        <span class="block text-xs text-slate-400">Add a book that does not exist in the library catalog yet.</span>
                    </label>
                    <label class="relative flex flex-col p-4 bg-slate-800/40 border border-slate-700 rounded-2xl cursor-pointer hover:border-slate-600 transition-all select-none">
                        <input type="radio" name="book_mode" value="existing" onclick="toggleBookMode('existing')" class="absolute top-4 right-4 text-indigo-600 focus:ring-indigo-500 h-4 w-4 border-slate-700 bg-slate-800">
                        <span class="block text-sm font-bold text-white mb-1">Add Copy to Existing Title</span>
                        <span class="block text-xs text-slate-400">Affix another copy to an existing catalog metadata record.</span>
                    </label>
                </div>
            </div>

            <!-- barcode scan (REQUIRED FOR BOTH) -->
            <div class="mb-8 p-6 bg-indigo-950/20 border border-indigo-500/20 rounded-2xl glow-purple">
                <div class="flex items-center justify-between mb-3">
                    <label for="barcode_id" class="text-sm font-bold text-indigo-300 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Scan Copy Barcode
                    </label>
                    <span class="text-[10px] bg-indigo-500/20 text-indigo-300 font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Required</span>
                </div>
                <input type="text" id="barcode_id" name="barcode_id" required autofocus value="{{ old('barcode_id') }}" class="w-full bg-slate-900 border border-indigo-500/40 rounded-xl py-3.5 px-4 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-mono tracking-widest text-center text-lg" placeholder="SCAN OR TYPE UNASSIGNED BARCODE">
                @error('barcode_id')
                    <p class="text-rose-400 text-xs mt-2 font-medium">{{ $message }}</p>
                @enderror
                <p class="text-slate-400 text-xs mt-2">Connect a USB barcode scanner and place your cursor inside the input field to link it instantly.</p>
            </div>

            <!-- Register New Title Fields -->
            <div id="new-title-fields" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="col-span-1 md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-slate-300 mb-2">Book Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" class="w-full bg-slate-800/50 border border-slate-700 rounded-xl py-3 px-4 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="isbn" class="block text-sm font-medium text-slate-300 mb-2">Barcode ID / ISBN (Optional)</label>
                    <input type="text" id="isbn" name="isbn" value="{{ old('isbn') }}" class="w-full bg-slate-800/50 border border-slate-700 rounded-xl py-3 px-4 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="Enter Barcode or ISBN if available">
                </div>

                <div>
                    <label for="author_id" class="block text-sm font-medium text-slate-300 mb-2">Author</label>
                    <select id="author_id" name="author_id" class="w-full bg-slate-800/50 border border-slate-700 rounded-xl py-3 px-4 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 appearance-none">
                        <option value="">Select Author</option>
                        @foreach(\App\Models\Author::all() as $author)
                            <option value="{{ $author->id }}" {{ old('author_id') == $author->id ? 'selected' : '' }}>{{ $author->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label for="category_id" class="block text-sm font-medium text-slate-300 mb-2">Category</label>
                    <select id="category_id" name="category_id" class="w-full bg-slate-800/50 border border-slate-700 rounded-xl py-3 px-4 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 appearance-none">
                        <option value="">Select Category</option>
                        @foreach(\App\Models\Category::all() as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Existing Title Selection Fields -->
            <div id="existing-title-fields" class="mb-8 hidden">
                <label for="book_id" class="block text-sm font-medium text-slate-300 mb-2">Select Existing Book Title</label>
                <div class="relative">
                    <select id="book_id" name="book_id" class="w-full bg-slate-800/50 border border-slate-700 rounded-xl py-3.5 px-4 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 appearance-none">
                        <option value="">Select a title from catalog...</option>
                        @foreach($books as $book)
                            <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>{{ $book->title }} (by {{ $book->author->name }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-700/50 flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-bold shadow-[0_4px_15px_rgba(79,70,229,0.3)] transition-colors">
                    Save Book & Copy
                </button>
            </div>
        </form>
    </div>

    <script>
        function toggleBookMode(mode) {
            const newFields = document.getElementById('new-title-fields');
            const existingFields = document.getElementById('existing-title-fields');
            
            const titleInput = document.getElementById('title');
            const authorSelect = document.getElementById('author_id');
            const categorySelect = document.getElementById('category_id');
            const bookSelect = document.getElementById('book_id');

            if (mode === 'new') {
                newFields.classList.remove('hidden');
                existingFields.classList.add('hidden');
                
                titleInput.required = true;
                authorSelect.required = true;
                categorySelect.required = true;
                bookSelect.required = false;
            } else {
                newFields.classList.add('hidden');
                existingFields.classList.remove('hidden');
                
                titleInput.required = false;
                authorSelect.required = false;
                categorySelect.required = false;
                bookSelect.required = true;
            }
        }

        // Initialize state on page load (in case of validation redirect)
        document.addEventListener('DOMContentLoaded', () => {
            const selectedMode = document.querySelector('input[name="book_mode"]:checked').value;
            toggleBookMode(selectedMode);
            
            // Keep autofocus on barcode scan input
            const barcodeInput = document.getElementById('barcode_id');
            if (barcodeInput) {
                barcodeInput.focus();
            }
        });
    </script>
@endsection

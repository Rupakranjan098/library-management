@extends('layouts.admin')

@section('title', 'Manage Books - Library Management')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Books</h1>
            <p class="text-slate-400 mt-1 text-sm">Manage the library's book catalog.</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="printSelectedBarcodes()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print Selected
            </button>
            <button onclick="printUnprintedBarcodes()" class="bg-violet-600 hover:bg-violet-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print Unprinted
            </button>
            <button onclick="toggleImportModal()" class="bg-[#1e293b] hover:bg-[#334155] text-indigo-400 hover:text-indigo-300 border border-slate-700 px-4 py-2 rounded-lg font-medium transition-colors flex items-center shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Import
            </button>
            <a href="{{ route('books.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium shadow-[0_4px_15px_rgba(79,70,229,0.3)] transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Add New Book
            </a>
        </div>
    </div>

    <!-- Data Table -->
    <div class="dark-card rounded-2xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-slate-700/50 flex justify-between items-center bg-slate-800/30">
            <form action="{{ route('books.index') }}" method="GET" class="relative w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" class="w-full bg-[#0f172a] border border-slate-700 rounded-lg py-1.5 pl-9 pr-4 text-sm text-slate-300 placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="Search books...">
            </form>
            <div class="text-slate-400 text-sm">
                Showing {{ $books->count() }} books
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-slate-400 text-xs uppercase tracking-wider bg-slate-800/20 border-b border-slate-700/80">
                        <th class="px-6 py-4 font-medium w-12 text-center">
                            <input type="checkbox" id="selectAllCheckbox" class="w-4 h-4 rounded bg-slate-800 border-slate-700 text-indigo-600 focus:ring-indigo-500">
                        </th>
                        <th class="px-6 py-4 font-medium">Title & Author</th>
                        <th class="px-6 py-4 font-medium">Category</th>
                        <th class="px-6 py-4 font-medium">ISBN</th>
                        <th class="px-6 py-4 font-medium">Copies</th>
                        <th class="px-6 py-4 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-700/50">
                    @forelse($books as $book)
                    <tr class="hover:bg-slate-800/30 transition-colors group">
                        <td class="px-6 py-4 w-12 text-center">
                            <input type="checkbox" name="book_ids[]" value="{{ $book->id }}" class="book-select-checkbox w-4 h-4 rounded bg-slate-800 border-slate-700 text-indigo-600 focus:ring-indigo-500">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-14 rounded bg-indigo-500/20 flex flex-col items-center justify-center text-indigo-400 mr-4 border border-indigo-500/30 shrink-0">
                                    <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                </div>
                                <div>
                                    <a href="{{ route('books.show', $book) }}" class="font-bold text-slate-200 hover:text-indigo-400 transition-colors">{{ $book->title }}</a>
                                    <div class="text-xs text-slate-500">{{ $book->author->name ?? 'Unknown Author' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-semibold bg-slate-700/50 text-slate-300 border border-slate-600">
                                {{ $book->category->name ?? 'General' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-400 font-mono text-xs">{{ $book->isbn }}</td>
                        <td class="px-6 py-4 text-slate-300 text-xs font-semibold whitespace-nowrap">
                            <span class="text-slate-200">{{ $book->copies->where('status', '!=', 'Retired')->count() }} Total</span>
                            <span class="text-slate-500 mx-1">&middot;</span>
                            <span class="text-indigo-400">{{ $book->copies->where('status', 'Available')->count() }} Available</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button type="button" onclick="toggleCopiesRow({{ $book->id }})" class="p-2 text-slate-400 hover:text-indigo-455 hover:bg-indigo-400/10 rounded transition-colors" title="View Physical Copies">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                </button>
                                <a href="{{ route('books.show', $book) }}" class="p-2 text-slate-400 hover:text-indigo-400 hover:bg-indigo-400/10 rounded transition-colors" title="View Details">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="{{ route('books.edit', $book) }}" class="p-2 text-slate-400 hover:text-indigo-400 hover:bg-indigo-400/10 rounded transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <form action="{{ route('books.destroy', $book) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this book?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-400 hover:bg-red-400/10 rounded transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <!-- Expandable Copies Sub-Table Row -->
                    <tr id="copies-row-{{ $book->id }}" class="hidden bg-slate-900/30">
                        <td colspan="6" class="px-8 py-4 border-t border-b border-slate-800/80">
                            <div class="p-4 bg-slate-900/60 rounded-xl border border-slate-700/50 shadow-inner">
                                <h4 class="text-xs font-bold text-slate-350 mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    Physical Copy List: "{{ $book->title }}"
                                </h4>
                                
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left border-collapse text-xs">
                                        <thead>
                                            <tr class="text-slate-500 border-b border-slate-850 uppercase tracking-wider font-semibold">
                                                <th class="pb-2 w-1/4">Barcode ID</th>
                                                <th class="pb-2 w-1/4">Status</th>
                                                <th class="pb-2 w-1/4">Assigned At</th>
                                                <th class="pb-2 w-1/4">Current Borrower</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-800/40 text-slate-300">
                                            @forelse($book->copies as $copy)
                                                <tr>
                                                    <td class="py-2 font-mono font-bold text-slate-200">{{ $copy->barcode_id }}</td>
                                                    <td class="py-2">
                                                        @php
                                                            $cClass = match($copy->status) {
                                                                'Available' => 'text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded',
                                                                'Issued' => 'text-blue-400 bg-blue-500/10 border border-blue-500/20 px-2 py-0.5 rounded',
                                                                'Overdue' => 'text-rose-400 bg-rose-500/10 border border-rose-500/20 px-2 py-0.5 rounded',
                                                                'Retired' => 'text-slate-400 bg-slate-500/10 border border-slate-700 px-2 py-0.5 rounded',
                                                                default => 'text-slate-300 bg-slate-700/50 px-2 py-0.5 rounded',
                                                            };
                                                        @endphp
                                                        <span class="{{ $cClass }}">{{ $copy->status }}</span>
                                                    </td>
                                                    <td class="py-2 text-slate-400">{{ $copy->assigned_at ? $copy->assigned_at->format('Y-m-d H:i') : '-' }}</td>
                                                    <td class="py-2">
                                                        @if($copy->status === 'Issued' || $copy->status === 'Overdue')
                                                            @php
                                                                $activeTx = $copy->transactions()->whereIn('status', ['Issued', 'Overdue'])->latest()->first();
                                                            @endphp
                                                            @if($activeTx && $activeTx->member)
                                                                <a href="{{ route('members.show', $activeTx->member) }}" class="text-indigo-400 hover:text-indigo-300 font-medium">{{ $activeTx->member->name }}</a>
                                                            @else
                                                                <span class="text-slate-500">-</span>
                                                            @endif
                                                        @else
                                                            <span class="text-slate-500">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="py-3 text-center text-slate-600">No copies registered.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-500">No books found in the library.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Import Modal -->
    <div id="importBooksModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="toggleImportModal()"></div>
        <!-- Modal Card -->
        <div class="dark-card rounded-2xl w-full max-w-lg p-6 relative z-10 shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-slate-700">
            <!-- Close Button -->
            <button onclick="toggleImportModal()" class="absolute top-4 right-4 text-slate-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <h3 class="text-xl font-bold text-white mb-2 flex items-center">
                <svg class="w-6 h-6 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Import Books via CSV
            </h3>
            <p class="text-slate-400 text-sm mb-4">Bulk import books into your library catalog. Missing authors or categories will be automatically registered.</p>
            
            <!-- Sample format box -->
            <div class="bg-[#0f172a] border border-slate-800 rounded-xl p-4 mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-xs text-indigo-300 font-bold">Required CSV Column Headers</span>
                    <a href="{{ route('books.template') }}" class="text-xs text-indigo-400 hover:text-indigo-300 flex items-center transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download Template
                    </a>
                </div>
                <code class="text-xs text-slate-300 font-mono block overflow-x-auto whitespace-nowrap bg-slate-900/60 p-2 rounded">
                    title, isbn, author, category, total_copies
                </code>
                <span class="text-[10px] text-slate-500 block mt-2">Example: "The Hobbit, 9780547928227, J.R.R. Tolkien, Fantasy, 5"</span>
            </div>

            <!-- Form -->
            <form action="{{ route('books.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-slate-300 text-sm font-medium mb-2">Select CSV File</label>
                    <input type="file" name="csv_file" accept=".csv,text/plain" required class="w-full bg-[#0f172a] border border-slate-700 rounded-xl p-3 text-sm text-slate-300 focus:outline-none focus:border-indigo-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 file:transition-colors">
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-700/50">
                    <button type="button" onclick="toggleImportModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-sm font-medium transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-colors flex items-center shadow-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        Upload and Import
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Barcode Modal -->
    <div id="barcodeModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="toggleBarcodeModal()"></div>
        <!-- Modal Card -->
        <div class="dark-card rounded-2xl w-full max-w-md p-6 relative z-10 shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-slate-700 text-center">
            <!-- Close Button -->
            <button onclick="toggleBarcodeModal()" class="absolute top-4 right-4 text-slate-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <h3 class="text-xl font-bold text-white mb-1">Book Barcode</h3>
            <p id="barcodeBookTitle" class="text-indigo-400 font-medium text-sm mb-4">Book Title</p>
            
            <!-- Barcode SVG Container -->
            <div class="bg-white p-6 rounded-xl inline-block mb-6 shadow-inner">
                <svg id="barcodeCanvas" class="mx-auto"></svg>
            </div>
            
            <div class="flex items-center justify-center space-x-3 pt-4 border-t border-slate-700/50">
                <button onclick="printBarcode()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-colors flex items-center shadow-lg">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print Barcode
                </button>
                <button onclick="downloadBarcode()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-sm font-medium transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Download SVG
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        let currentBarcodeIsbn = '';

        function toggleImportModal() {
            const modal = document.getElementById('importBooksModal');
            if (modal) {
                modal.classList.toggle('hidden');
            }
        }

        function toggleBarcodeModal() {
            const modal = document.getElementById('barcodeModal');
            if (modal) {
                modal.classList.toggle('hidden');
            }
        }

        function showBarcode(title, isbn) {
            currentBarcodeIsbn = isbn;
            document.getElementById('barcodeBookTitle').innerText = title;
            toggleBarcodeModal();
            
            // Clean up old barcode
            const canvas = document.getElementById('barcodeCanvas');
            canvas.innerHTML = '';
            
            // Generate new barcode (using standard CODE128 format or format auto-detection)
            setTimeout(() => {
                try {
                    JsBarcode("#barcodeCanvas", isbn, {
                        format: "auto",
                        lineColor: "#000",
                        width: 2,
                        height: 70,
                        displayValue: true,
                        fontSize: 14,
                        font: "monospace",
                        background: "#ffffff"
                    });
                } catch (e) {
                    console.error("Barcode generation failed", e);
                }
            }, 100);
        }

        function downloadBarcode() {
            const svgElement = document.getElementById('barcodeCanvas');
            const svgString = new XMLSerializer().serializeToString(svgElement);
            const svgBlob = new Blob([svgString], { type: 'image/svg+xml;charset=utf-8' });
            const svgUrl = URL.createObjectURL(svgBlob);
            const downloadLink = document.createElement('a');
            downloadLink.href = svgUrl;
            downloadLink.download = `${currentBarcodeIsbn}_barcode.svg`;
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }

        function printBarcode() {
            const printWindow = window.open('', '_blank');
            const svgElement = document.getElementById('barcodeCanvas').outerHTML;
            printWindow.document.write(`
                <html>
                <head>
                    <title>Print Barcode - ${currentBarcodeIsbn}</title>
                    <style>
                        body {
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            justify-content: center;
                            height: 100vh;
                            margin: 0;
                            font-family: system-ui, sans-serif;
                        }
                        .title {
                            font-size: 18px;
                            font-weight: bold;
                            margin-bottom: 10px;
                            text-align: center;
                        }
                        svg {
                            max-width: 100%;
                        }
                    </style>
                </head>
                <body onload="window.print(); window.close();">
                    <div class="title">${document.getElementById('barcodeBookTitle').innerText}</div>
                    ${svgElement}
                </body>
                </html>
            `);
            printWindow.document.close();
        }

        // Toggle all checkboxes
        document.addEventListener('DOMContentLoaded', function () {
            const selectAll = document.getElementById('selectAllCheckbox');
            const checkboxes = document.querySelectorAll('.book-select-checkbox');

            if (selectAll) {
                selectAll.addEventListener('change', function(e) {
                    checkboxes.forEach(cb => {
                        cb.checked = e.target.checked;
                    });
                });
            }

            checkboxes.forEach(cb => {
                cb.addEventListener('change', function () {
                    if (!this.checked && selectAll) {
                        selectAll.checked = false;
                    } else if (selectAll && document.querySelectorAll('.book-select-checkbox:checked').length === checkboxes.length) {
                        selectAll.checked = true;
                    }
                });
            });
        });

        // Submit selected books for barcode printing
        function printSelectedBarcodes() {
            const checkboxes = document.querySelectorAll('.book-select-checkbox:checked');
            if (checkboxes.length === 0) {
                alert('Please select at least one book to print.');
                return;
            }
            
            const ids = Array.from(checkboxes).map(cb => cb.value).join(',');
            window.location.href = `{{ route('books.print-barcodes') }}?ids=${ids}&by_book=1`;
        }

        function printUnprintedBarcodes() {
            window.location.href = `{{ route('books.print-barcodes') }}?unprinted=1`;
        }

        function toggleCopiesRow(bookId) {
            const row = document.getElementById(`copies-row-${bookId}`);
            if (row) {
                row.classList.toggle('hidden');
            }
        }
    </script>
    @endpush
@endsection

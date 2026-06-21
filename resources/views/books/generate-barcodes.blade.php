@extends('layouts.admin')

@section('title', 'Generate Barcodes - Library Management')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Generate Barcodes</h1>
            <p class="text-slate-400 mt-1 text-sm">Bulk pre-generate and print unassigned sequential barcode labels.</p>
        </div>
        <a href="{{ route('books.index') }}" class="text-slate-400 hover:text-white transition-colors flex items-center bg-slate-800/50 hover:bg-slate-700/50 px-4 py-2 rounded-xl border border-slate-700">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Books
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Generator Form Card -->
        <div class="lg:col-span-2">
            <div class="dark-card rounded-3xl p-8">
                <h3 class="text-lg font-bold text-white mb-4">Select Quantity</h3>
                <form action="{{ route('books.generate-barcodes.post') }}" method="POST">
                    @csrf
                    
                    <!-- Presets Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-300 mb-3">Quick Presets</label>
                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                            <button type="button" onclick="setQuantity(10)" class="preset-btn bg-slate-800/50 hover:bg-slate-850 hover:text-indigo-400 text-slate-300 font-medium py-3 px-4 rounded-xl border border-slate-700 transition-all text-center">10</button>
                            <button type="button" onclick="setQuantity(50)" class="preset-btn bg-slate-800/50 hover:bg-slate-850 hover:text-indigo-400 text-slate-300 font-medium py-3 px-4 rounded-xl border border-slate-700 transition-all text-center">50</button>
                            <button type="button" onclick="setQuantity(100)" class="preset-btn bg-slate-800/50 hover:bg-slate-850 hover:text-indigo-400 text-slate-300 font-medium py-3 px-4 rounded-xl border border-slate-700 transition-all text-center active-preset">100</button>
                            <button type="button" onclick="setQuantity(200)" class="preset-btn bg-slate-800/50 hover:bg-slate-850 hover:text-indigo-400 text-slate-300 font-medium py-3 px-4 rounded-xl border border-slate-700 transition-all text-center">200</button>
                            <button type="button" onclick="setQuantity(500)" class="preset-btn bg-slate-800/50 hover:bg-slate-850 hover:text-indigo-400 text-slate-300 font-medium py-3 px-4 rounded-xl border border-slate-700 transition-all text-center">500</button>
                        </div>
                    </div>

                    <!-- Custom Input -->
                    <div class="mb-8">
                        <label for="quantity" class="block text-sm font-medium text-slate-300 mb-2">Custom Quantity</label>
                        <div class="relative max-w-xs">
                            <input type="number" id="quantity" name="quantity" min="1" max="500" value="100" required class="w-full bg-slate-800/50 border border-slate-700 rounded-xl py-3 px-4 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-semibold">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400 text-xs">
                                Max 500
                            </div>
                        </div>
                        <p class="text-slate-400 text-xs mt-2">Sequential codes will be generated starting from the next available unique identifier.</p>
                    </div>

                    <div class="pt-6 border-t border-slate-700/50 flex justify-end">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-bold shadow-[0_4px_15px_rgba(79,70,229,0.3)] transition-colors flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Generate & Print
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Card -->
        <div>
            <div class="dark-card rounded-3xl p-8 h-full flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 text-indigo-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        How it works
                    </h3>
                    <ul class="space-y-4 text-sm text-slate-300">
                        <li class="flex items-start">
                            <span class="bg-indigo-500/20 text-indigo-400 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5 shrink-0">1</span>
                            <span>Specify how many barcodes you want to print.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="bg-indigo-500/20 text-indigo-400 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5 shrink-0">2</span>
                            <span>The system registers them as **Unassigned** copies in `book_copies` with no link to a specific catalog book.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="bg-indigo-500/20 text-indigo-400 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5 shrink-0">3</span>
                            <span>Print the sheets to stickers, automatically logging the print date.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="bg-indigo-500/20 text-indigo-400 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5 shrink-0">4</span>
                            <span>Affine labels to physical books, then assign the barcode IDs inside the Books panel later.</span>
                        </li>
                    </ul>
                </div>
                <div class="mt-8 pt-6 border-t border-slate-700/50">
                    <p class="text-xs text-slate-400">All generations are transactionally row-locked for safety across simultaneous operations.</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .active-preset {
            background-color: rgb(79 70 229 / var(--tw-bg-opacity, 1)) !important;
            border-color: transparent !important;
            color: white !important;
            box-shadow: 0 4px 15px rgba(79,70,229,0.3);
        }
    </style>

    <script>
        function setQuantity(val) {
            document.getElementById('quantity').value = val;
            
            // Toggle active classes
            document.querySelectorAll('.preset-btn').forEach(btn => {
                btn.classList.remove('active-preset');
                if (parseInt(btn.innerText) === val) {
                    btn.classList.add('active-preset');
                }
            });
        }

        // Sync input changes to presets
        document.getElementById('quantity').addEventListener('input', function() {
            const val = parseInt(this.value);
            document.querySelectorAll('.preset-btn').forEach(btn => {
                btn.classList.remove('active-preset');
                if (parseInt(btn.innerText) === val) {
                    btn.classList.add('active-preset');
                }
            });
        });
    </script>
@endsection

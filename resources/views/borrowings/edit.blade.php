@extends('layouts.admin')

@section('title', 'Edit Borrowing Record - Library Management')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Edit Borrowing Record</h1>
            <p class="text-slate-400 mt-1 text-sm">Update borrowing details.</p>
        </div>
        <a href="{{ route('borrowings.index') }}" class="text-slate-400 hover:text-white transition-colors flex items-center bg-slate-800/50 hover:bg-slate-700/50 px-4 py-2 rounded-xl border border-slate-700">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Records
        </a>
    </div>

    <div class="dark-card rounded-3xl p-8 max-w-3xl">
        <form action="{{ route('borrowings.update', $borrowing) }}" method="POST">
            @csrf
            @method('PUT')
            
            @if($borrowing->fine > 0)
                <div class="mb-6 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-between shadow-md">
                    <div class="flex items-center space-x-3">
                        <div class="bg-rose-500/20 p-2 rounded-xl border border-rose-500/30 text-rose-400">
                            <span class="text-xl font-bold">₹</span>
                        </div>
                        <div>
                            <span class="font-bold text-sm block">Overdue Fine Calculated</span>
                            <span class="text-xs text-slate-400">This record currently has an accumulated fine of <strong>₹{{ number_format($borrowing->fine) }}</strong>.</span>
                        </div>
                    </div>
                    <div class="bg-rose-500/20 px-3 py-1.5 rounded-lg border border-rose-500/30 text-xs font-bold shrink-0">
                        ₹{{ $borrowing->fine }}
                    </div>
                </div>
            @endif
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Member Name</label>
                    <input type="text" name="member_name" list="members-list" required placeholder="Enter member name" class="w-full bg-slate-800/50 border border-slate-700 rounded-xl py-3 px-4 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" autocomplete="off" value="{{ old('member_name', $borrowing->member->name ?? '') }}">
                    <datalist id="members-list">
                        @foreach(\App\Models\Member::all() as $member)
                            <option value="{{ $member->name }}">
                        @endforeach
                    </datalist>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Book Barcode ID</label>
                    <input type="text" name="barcode_id" id="barcode_id" required autofocus placeholder="Scan or type barcode (e.g. LIB-000001)" list="books-list" class="w-full bg-slate-800/50 border border-slate-700 rounded-xl py-3 px-4 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" autocomplete="off" value="{{ old('barcode_id', $borrowing->book->barcode_id ?? '') }}">
                    <datalist id="books-list">
                        @foreach(\App\Models\Book::all() as $book)
                            <option value="{{ $book->barcode_id }}">{{ $book->title }}</option>
                        @endforeach
                    </datalist>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Borrow Date</label>
                    <input type="text" name="borrow_date" id="borrow_date" value="{{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('Y-m-d') }}" required class="datepicker w-full bg-slate-800/50 border border-slate-700 rounded-xl py-3 px-4 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="YYYY-MM-DD">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Due Date</label>
                    <input type="text" name="due_date" id="due_date" value="{{ \Carbon\Carbon::parse($borrowing->due_date)->format('Y-m-d') }}" required class="datepicker w-full bg-slate-800/50 border border-slate-700 rounded-xl py-3 px-4 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="YYYY-MM-DD">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Status</label>
                    <select name="status" required class="w-full bg-slate-800/50 border border-slate-700 rounded-xl py-3 px-4 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 appearance-none">
                        <option value="borrowed" {{ $borrowing->status == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                        <option value="returned" {{ $borrowing->status == 'returned' ? 'selected' : '' }}>Returned</option>
                        <option value="overdue" {{ $borrowing->status == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-700/50 flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-medium shadow-[0_4px_15px_rgba(79,70,229,0.3)] transition-colors">
                    Update Record
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const borrowDateInput = document.getElementById('borrow_date');
            const dueDateInput = document.getElementById('due_date');
            const borrowDuration = {{ (int) \App\Models\Setting::get('borrow_duration', 15) }};

            borrowDateInput.addEventListener('change', function() {
                if (this.value) {
                    const borrowDate = new Date(this.value);
                    borrowDate.setDate(borrowDate.getDate() + borrowDuration);
                    
                    // Format to YYYY-MM-DD
                    const year = borrowDate.getFullYear();
                    const month = String(borrowDate.getMonth() + 1).padStart(2, '0');
                    const day = String(borrowDate.getDate()).padStart(2, '0');
                    
                    dueDateInput.value = `${year}-${month}-${day}`;
                }
            });
        });
    </script>
@endsection

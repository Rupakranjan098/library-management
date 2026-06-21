@extends('layouts.admin')

@section('title', 'Return Book - Library Management')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white tracking-tight">Return Book</h1>
                <p class="text-slate-400 mt-1 text-sm font-medium">Scan a book's barcode to verify checkout status and log returns.</p>
            </div>
            <a href="{{ route('circulation.index') }}" class="bg-[#1e293b] hover:bg-[#334155] border border-slate-700 text-slate-300 px-4 py-2 rounded-lg font-medium transition-colors text-sm">
                Back to Circulation
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Scan Input Column -->
            <div class="lg:col-span-1 space-y-6">
                <div class="dark-card rounded-2xl p-6 shadow-lg">
                    <h2 class="text-lg font-bold text-white mb-4">Scan Barcode</h2>
                    <form action="{{ route('circulation.return') }}" method="GET" class="space-y-4">
                        <x-scan-barcode 
                            id="barcode_id" 
                            name="barcode_id" 
                            label="Scan Book Barcode" 
                            placeholder="Scan barcode to check status..." 
                            value="{{ request('barcode_id') }}"
                        />
                        <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-medium transition-colors shadow-lg">
                            Lookup Book
                        </button>
                    </form>
                </div>
            </div>

            <!-- Transaction Details Column -->
            <div class="lg:col-span-2">
                @if(request()->filled('barcode_id'))
                    @if($activeTransaction)
                        <div class="dark-card rounded-2xl p-6 shadow-lg space-y-6">
                            <div class="flex items-start justify-between border-b border-slate-700/50 pb-4">
                                <div>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20 uppercase">
                                        Active Checkout Found
                                    </span>
                                    <h3 class="text-xl font-bold text-white mt-2">{{ $book->title }}</h3>
                                    <p class="text-slate-400 text-xs mt-1 font-mono">Barcode: {{ $copy->barcode_id }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-slate-400 text-xs font-semibold uppercase tracking-wide">Fine Rate</p>
                                    <p class="text-lg font-bold text-indigo-400">₹{{ number_format(config('circulation.fine_rate', 2.00), 2) }} <span class="text-xs font-normal text-slate-500">/ day</span></p>
                                </div>
                            </div>

                            <!-- Member Info -->
                            <div class="grid grid-cols-2 gap-4 bg-slate-800/20 p-4 rounded-xl border border-slate-700/50">
                                <div>
                                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Borrower</p>
                                    <p class="text-sm font-bold text-slate-200 mt-0.5">{{ $activeTransaction->member->name }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $activeTransaction->member->email }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Member Type</p>
                                    <p class="text-sm font-bold text-slate-200 mt-0.5 uppercase">{{ $activeTransaction->member->member_type ?? 'student' }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Phone: {{ $activeTransaction->member->phone ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <!-- Timeline Info -->
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Checked Out</p>
                                    <p class="text-sm font-bold text-slate-300 mt-0.5">{{ $activeTransaction->issue_date->format('Y-m-d H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Due Date</p>
                                    <p class="text-sm font-bold text-slate-300 mt-0.5">{{ $activeTransaction->due_date->format('Y-m-d') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Overdue Status</p>
                                    @if($daysOverdue > 0)
                                        <span class="text-xs font-bold text-rose-400 flex items-center mt-1">
                                            <span class="h-2 w-2 rounded-full bg-rose-500 mr-1.5 animate-pulse"></span>
                                            {{ $daysOverdue }} days late
                                        </span>
                                    @else
                                        <span class="text-xs font-bold text-emerald-400 flex items-center mt-1">
                                            <span class="h-2 w-2 rounded-full bg-emerald-500 mr-1.5"></span>
                                            On time
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Fine Preview Card -->
                            @if($fineAmount > 0)
                                <div class="bg-rose-950/20 border border-rose-500/20 p-4 rounded-xl flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-rose-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M3 19h18a1 1 0 00.728-1.686L12.728 3.314a1 1 0 00-1.456 0L2.272 17.314A1 1 0 003 19z"></path></svg>
                                        <div>
                                            <p class="text-sm font-bold text-rose-200">Overdue Penalty Due</p>
                                            <p class="text-xs text-rose-400">Late fees will be recorded upon processing return.</p>
                                        </div>
                                    </div>
                                    <p class="text-xl font-mono font-bold text-rose-400">₹{{ number_format($fineAmount, 2) }}</p>
                                </div>
                            @endif

                            <!-- Action Form -->
                            <form action="{{ route('circulation.return.post') }}" method="POST" class="border-t border-slate-700/50 pt-5 flex justify-end">
                                @csrf
                                <input type="hidden" name="barcode_id" value="{{ $copy->barcode_id }}">
                                <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold transition-colors shadow-lg shadow-emerald-600/20 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2-2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    Confirm and Process Return
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- No open transactions found alert -->
                        <div class="bg-amber-950/20 border border-amber-500/20 rounded-2xl p-6 shadow-lg text-center space-y-4">
                            <div class="mx-auto w-12 h-12 bg-amber-500/10 border border-amber-500/20 rounded-full flex items-center justify-center text-amber-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M3 19h18a1 1 0 00.728-1.686L12.728 3.314a1 1 0 00-1.456 0L2.272 17.314A1 1 0 003 19z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-amber-200">No Open Checkout Found</h3>
                                <p class="text-sm text-slate-400 mt-2">The book with barcode <span class="font-mono text-amber-400 font-bold">{{ request('barcode_id') }}</span> is either already returned or has not been issued to any member.</p>
                            </div>
                            <div class="border-t border-slate-700/30 pt-4">
                                <a href="{{ route('circulation.return') }}" class="text-indigo-400 hover:text-indigo-300 text-sm font-semibold transition-colors">Clear and scan again</a>
                            </div>
                        </div>
                    @endif
                @else
                    <!-- Empty State / Scan Prompt -->
                    <div class="border border-dashed border-slate-700 rounded-2xl p-12 text-center text-slate-500 bg-slate-800/10 shadow-inner flex flex-col items-center justify-center h-full min-h-[300px]">
                        <svg class="w-12 h-12 text-slate-600 mb-4 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2zM9 16V8m3 8V8m3 8V8m-6 0h6m-6 8h6"></path></svg>
                        <h3 class="text-lg font-bold text-slate-400">Awaiting Barcode Scan</h3>
                        <p class="text-sm text-slate-500 mt-2 max-w-sm">Position your barcode scanner and scan a book label. The details will populate automatically for checkout verification.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

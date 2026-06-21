@extends('layouts.admin')

@section('title', 'Overdue Dashboard - Library Management')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Overdue Dashboard</h1>
            <p class="text-slate-400 mt-1 text-sm font-medium">Monitor open checkouts that have exceeded their due date.</p>
        </div>
        <div class="bg-rose-500/10 border border-rose-500/30 rounded-xl px-4 py-2 flex items-center text-rose-400 shadow-md">
            <span class="h-2.5 w-2.5 rounded-full bg-rose-500 mr-2 animate-ping"></span>
            <span class="font-bold text-sm">Overdue Fines: ₹{{ number_format(config('circulation.fine_rate', 2.00), 2) }} / day</span>
        </div>
    </div>

    <!-- Stats summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="dark-card p-5 rounded-2xl flex items-center">
            <div class="p-3.5 bg-rose-500/10 text-rose-400 border border-rose-500/20 rounded-xl mr-4 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M3 19h18a1 1 0 00.728-1.686L12.728 3.314a1 1 0 00-1.456 0L2.272 17.314A1 1 0 003 19z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Overdue Items</p>
                <h3 class="text-2xl font-extrabold text-white mt-1">{{ $overdueTransactions->total() }}</h3>
            </div>
        </div>

        <div class="dark-card p-5 rounded-2xl flex items-center">
            <div class="p-3.5 bg-amber-500/10 text-amber-400 border border-amber-500/20 rounded-xl mr-4 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Accumulated Unpaid Fines</p>
                <h3 class="text-2xl font-extrabold text-amber-400 mt-1">
                    @php
                        $totalFines = $overdueTransactions->sum(function($t) {
                            if (now()->greaterThan($t->due_date)) {
                                return now()->diffInDays($t->due_date) * config('circulation.fine_rate', 2.00);
                            }
                            return 0.00;
                        });
                    @endphp
                    ₹{{ number_format($totalFines, 2) }}
                </h3>
            </div>
        </div>

        <div class="dark-card p-5 rounded-2xl flex items-center">
            <div class="p-3.5 bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 rounded-xl mr-4 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Quick Actions</p>
                <a href="{{ route('circulation.return') }}" class="text-xs font-bold text-indigo-400 hover:text-indigo-300 mt-2 block transition-colors">Go to Return Desk &rarr;</a>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="dark-card rounded-2xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-slate-700/50 flex justify-between items-center bg-slate-800/30">
            <form action="{{ route('circulation.overdue') }}" method="GET" class="relative w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" class="w-full bg-[#0f172a] border border-slate-700 rounded-lg py-1.5 pl-9 pr-4 text-sm text-slate-300 placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="Search borrower or book...">
            </form>
            <div class="text-slate-400 text-sm">
                Showing {{ $overdueTransactions->count() }} overdue loans
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-slate-400 text-xs uppercase tracking-wider bg-slate-800/20 border-b border-slate-700/80">
                        <th class="px-6 py-4 font-medium">Book Details</th>
                        <th class="px-6 py-4 font-medium">Member Details</th>
                        <th class="px-6 py-4 font-medium">Due Date</th>
                        <th class="px-6 py-4 font-medium">Days Overdue</th>
                        <th class="px-6 py-4 font-medium text-right">Accumulated Fine</th>
                        <th class="px-6 py-4 font-medium text-right w-36">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-700/50">
                    @forelse($overdueTransactions as $transaction)
                    @php
                        $daysLate = now()->diffInDays($transaction->due_date);
                        $currentFine = $daysLate * config('circulation.fine_rate', 2.00);
                    @endphp
                    <tr class="hover:bg-slate-800/30 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-10 rounded bg-indigo-500/10 flex flex-col items-center justify-center text-indigo-400 mr-3 border border-indigo-500/20 shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                </div>
                                <div>
                                    @if($transaction->bookCopy && $transaction->bookCopy->book)
                                        <a href="{{ route('books.show', $transaction->bookCopy->book) }}" class="font-bold text-slate-200 hover:text-indigo-400 transition-colors">{{ $transaction->bookCopy->book->title }}</a>
                                    @else
                                        <span class="font-bold text-slate-400">Unassigned Title</span>
                                    @endif
                                    <div class="text-xs text-slate-500 font-mono">{{ $transaction->bookCopy->barcode_id ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <a href="{{ route('members.show', $transaction->member) }}" class="font-semibold text-slate-200 hover:text-indigo-400 transition-colors">{{ $transaction->member->name }}</a>
                                <div class="text-xs text-slate-400 mt-0.5">Email: {{ $transaction->member->email }}</div>
                                <div class="text-xs text-slate-500 mt-0.5">Phone: {{ $transaction->member->phone ?? 'N/A' }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-300">
                            {{ $transaction->due_date->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-rose-400 font-bold">
                                {{ $daysLate }} days
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-mono font-bold text-rose-400">
                            ₹{{ number_format($currentFine, 2) }}
                        </td>
                        <td class="px-6 py-4 text-right w-36">
                            @if($transaction->bookCopy)
                                <a href="{{ route('circulation.return', ['barcode_id' => $transaction->bookCopy->barcode_id]) }}" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md text-xs font-bold transition-colors shadow">
                                    Check In
                                </a>
                            @else
                                <span class="text-slate-500">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-500 font-medium">No overdue books at the moment. Good job!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($overdueTransactions->hasPages())
            <div class="p-5 border-t border-slate-700/50 flex justify-center bg-slate-800/10">
                {{ $overdueTransactions->links() }}
            </div>
        @endif
    </div>
@endsection

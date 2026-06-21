@extends('layouts.admin')

@section('title', 'Book Circulation - Library Management')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Book Circulation</h1>
            <p class="text-slate-400 mt-1 text-sm font-medium">Track and manage book checkouts, returns, and overdue items.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('circulation.issue') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium shadow-[0_4px_15px_rgba(79,70,229,0.3)] transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                Issue Book
            </a>
            <a href="{{ route('circulation.return') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-medium shadow-[0_4px_15px_rgba(16,185,129,0.3)] transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                Return Book
            </a>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="dark-card rounded-2xl p-5 mb-6 shadow-sm">
        <form action="{{ route('circulation.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Title, barcode, or member name..." class="w-full bg-[#0f172a] border border-slate-700 rounded-lg py-2 px-3 text-sm text-slate-300 placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            </div>

            <div>
                <label for="status" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Status</label>
                <select name="status" id="status" class="w-full bg-[#0f172a] border border-slate-700 rounded-lg py-2 px-3 text-sm text-slate-300 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    <option value="">All Statuses</option>
                    <option value="Issued" {{ request('status') === 'Issued' ? 'selected' : '' }}>Issued</option>
                    <option value="Returned" {{ request('status') === 'Returned' ? 'selected' : '' }}>Returned</option>
                    <option value="Overdue" {{ request('status') === 'Overdue' ? 'selected' : '' }}>Overdue</option>
                    <option value="Lost" {{ request('status') === 'Lost' ? 'selected' : '' }}>Lost</option>
                </select>
            </div>

            <div>
                <label for="start_date" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Issued From</label>
                <input type="text" name="start_date" id="start_date" value="{{ request('start_date') }}" class="datepicker w-full bg-[#0f172a] border border-slate-700 rounded-lg py-2 px-3 text-sm text-slate-300 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="YYYY-MM-DD">
            </div>

            <div class="flex items-end space-x-2">
                <div class="flex-1">
                    <label for="end_date" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Issued To</label>
                    <input type="text" name="end_date" id="end_date" value="{{ request('end_date') }}" class="datepicker w-full bg-[#0f172a] border border-slate-700 rounded-lg py-2 px-3 text-sm text-slate-300 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="YYYY-MM-DD">
                </div>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors h-[38px] flex items-center justify-center">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'status', 'start_date', 'end_date']))
                    <a href="{{ route('circulation.index') }}" class="bg-slate-700 hover:bg-slate-600 text-slate-300 px-3 py-2 rounded-lg font-medium transition-colors h-[38px] flex items-center justify-center">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="dark-card rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-slate-400 text-xs uppercase tracking-wider bg-slate-800/20 border-b border-slate-700/80">
                        <th class="px-6 py-4 font-medium">Book Details</th>
                        <th class="px-6 py-4 font-medium">Member</th>
                        <th class="px-6 py-4 font-medium">Issue Date</th>
                        <th class="px-6 py-4 font-medium">Due Date</th>
                        <th class="px-6 py-4 font-medium">Return Date</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                        <th class="px-6 py-4 font-medium text-right">Fine Amount</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-700/50">
                    @forelse($transactions as $transaction)
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
                                <div class="text-xs text-slate-500">{{ $transaction->member->email }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-300">
                            {{ $transaction->issue_date->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4 text-slate-300">
                            {{ $transaction->due_date->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4 text-slate-300">
                            {{ $transaction->return_date ? $transaction->return_date->format('Y-m-d') : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $badgeClass = match($transaction->status) {
                                    'Issued' => 'bg-blue-500/10 text-blue-400 border border-blue-500/20',
                                    'Returned' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
                                    'Overdue' => 'bg-rose-500/10 text-rose-400 border border-rose-500/20 glow-orange',
                                    'Lost' => 'bg-slate-500/15 text-slate-400 border border-slate-700',
                                    default => 'bg-slate-700/50 text-slate-300 border border-slate-600',
                                };
                            @endphp
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $badgeClass }}">
                                {{ $transaction->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-mono font-bold {{ $transaction->fine_amount > 0 ? 'text-rose-400' : 'text-slate-500' }}">
                            ₹{{ number_format($transaction->fine_amount, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-500 font-medium">No circulation transactions found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
            <div class="p-5 border-t border-slate-700/50 flex justify-center bg-slate-800/10">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
@endsection

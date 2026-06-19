@extends('layouts.admin')

@section('title', 'Manage Borrowings - Library Management')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Borrow Records</h1>
            <p class="text-slate-400 mt-1 text-sm">Manage all book issuances and returns.</p>
        </div>
        <a href="{{ route('borrowings.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium shadow-[0_4px_15px_rgba(34,197,94,0.3)] transition-colors flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Issue a Book
        </a>
    </div>

    <!-- Data Table -->
    <div class="dark-card rounded-2xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-slate-700/50 flex justify-between items-center bg-slate-800/30">
            <form action="{{ route('borrowings.index') }}" method="GET" class="relative w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" class="w-full bg-[#0f172a] border border-slate-700 rounded-lg py-1.5 pl-9 pr-4 text-sm text-slate-300 placeholder-slate-500 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500" placeholder="Search records...">
            </form>
            <div class="text-slate-400 text-sm">
                Showing {{ $borrowings->count() }} records
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-slate-400 text-xs uppercase tracking-wider bg-slate-800/20 border-b border-slate-700/80">
                        <th class="px-6 py-4 font-medium">Member</th>
                        <th class="px-6 py-4 font-medium">Book</th>
                        <th class="px-6 py-4 font-medium">Issue & Due Date</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                        <th class="px-6 py-4 font-medium">Fine</th>
                        <th class="px-6 py-4 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-700/50">
                    @forelse($borrowings as $record)
                    <tr class="hover:bg-slate-800/30 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center font-bold text-xs text-white mr-3 shrink-0">
                                    {{ substr($record->member->name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <span class="font-bold text-slate-200">{{ $record->member->name ?? 'Unknown' }}</span>
                                    <div class="text-[10px] text-slate-500">ID: #{{ str_pad($record->member_id, 5, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-200">{{ $record->book->title ?? 'Unknown Book' }}</div>
                            <div class="text-[10px] text-slate-500">ISBN: {{ $record->book->isbn ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-400 text-xs">
                            <div class="mb-1"><span class="text-slate-500">Issued:</span> {{ \Carbon\Carbon::parse($record->borrow_date)->format('M d, Y') }}</div>
                            <div><span class="text-slate-500">Due:</span> <span class="{{ \Carbon\Carbon::parse($record->due_date)->isPast() && $record->status !== 'returned' ? 'text-red-400 font-bold' : '' }}">{{ \Carbon\Carbon::parse($record->due_date)->format('M d, Y') }}</span></div>
                        </td>
                        <td class="px-6 py-4">
                            @if($record->status === 'borrowed')
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20">Issued</span>
                            @elseif($record->status === 'overdue')
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-semibold bg-red-500/10 text-red-400 border border-red-500/20">Overdue</span>
                            @else
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-semibold bg-green-500/10 text-green-400 border border-green-500/20">Returned</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($record->fine > 0)
                                <div class="flex flex-col">
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20 w-max">₹{{ $record->fine }}</span>
                                    <span class="text-[10px] text-slate-500 mt-1 font-medium">{{ $record->days_overdue }} {{ $record->days_overdue == 1 ? 'day' : 'days' }} late</span>
                                </div>
                            @else
                                <span class="text-slate-500 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('borrowings.edit', $record) }}" class="p-2 text-slate-400 hover:text-indigo-400 hover:bg-indigo-400/10 rounded transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <form action="{{ route('borrowings.destroy', $record) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-400 hover:bg-red-400/10 rounded transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-500">No borrowing records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

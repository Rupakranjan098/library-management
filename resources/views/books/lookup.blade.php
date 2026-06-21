@extends('layouts.admin')

@section('title', 'Barcode Lookup - Library Management')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Barcode Lookup</h1>
            <p class="text-slate-400 mt-1 text-sm">Scan any barcode label to verify physical items, catalog records, and circulation logs.</p>
        </div>
    </div>

    <!-- Search Card -->
    <div class="dark-card rounded-3xl p-6 mb-6 max-w-2xl bg-indigo-950/10 border-indigo-500/20 shadow-[0_4px_25px_rgba(79,70,229,0.1)]">
        <form action="{{ route('books.lookup') }}" method="GET" id="lookupForm" class="space-y-4">
            <div class="flex items-center justify-between">
                <label for="barcode_id" class="text-sm font-bold text-indigo-300 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Scan Barcode Sticker
                </label>
                <span class="text-[10px] text-slate-500 font-mono">Autofocused</span>
            </div>
            <div class="relative">
                <input type="text" id="barcode_id" name="barcode_id" required autofocus value="{{ $barcodeId }}" class="w-full bg-slate-900 border border-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-xl py-3.5 px-4 text-slate-200 font-mono tracking-widest text-center text-lg focus:outline-none" placeholder="SCAN OR ENTER BARCODE">
                <button type="submit" class="absolute inset-y-1 right-1 px-5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-all flex items-center">
                    Lookup
                </button>
            </div>
        </form>
    </div>

    <!-- Flash Error Message -->
    @if(session('error_lookup'))
        <div class="bg-rose-950/80 border border-rose-500/30 text-rose-200 rounded-2xl p-5 mb-6 max-w-2xl flex items-start shadow-lg">
            <div class="bg-rose-500/20 p-2 rounded-xl text-rose-400 border border-rose-500/20 mr-4 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M3 19h18a1 1 0 00.728-1.686L12.728 3.314a1 1 0 00-1.456 0L2.272 17.314A1 1 0 003 19z"></path></svg>
            </div>
            <div>
                <h4 class="font-bold text-sm text-white mb-1">Lookup Failed</h4>
                <p class="text-xs text-slate-350">{{ session('error_lookup') }}</p>
            </div>
        </div>
    @endif

    <!-- Results Section -->
    @if($copy)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Copy & Book Info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Physical Copy Status Card -->
                <div class="dark-card rounded-2xl p-6 shadow-sm border border-slate-700/40">
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4">Physical Item Details</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <span class="text-xs text-slate-500 block">Barcode ID</span>
                            <span class="text-lg font-mono font-extrabold text-slate-200 mt-1 block">{{ $copy->barcode_id }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-500 block">Current Status</span>
                            @php
                                $statusClass = match($copy->status) {
                                    'Available' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
                                    'Issued' => 'bg-blue-500/10 text-blue-400 border border-blue-500/20',
                                    'Overdue' => 'bg-rose-500/10 text-rose-400 border border-rose-500/20 glow-orange',
                                    'Retired' => 'bg-slate-500/15 text-slate-400 border border-slate-700',
                                    'Unassigned' => 'bg-amber-500/10 text-amber-400 border border-amber-500/25',
                                    default => 'bg-slate-700/50 text-slate-300 border border-slate-600',
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-md text-xs font-bold uppercase tracking-wide inline-block mt-1.5 {{ $statusClass }}">
                                {{ $copy->status }}
                            </span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-500 block">Linked At (Assigned)</span>
                            <span class="text-sm font-semibold text-slate-300 mt-1 block">{{ $copy->assigned_at ? $copy->assigned_at->format('Y-m-d H:i') : 'Unassigned (No Book Metadata linked yet)' }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-500 block">Barcode Printed At</span>
                            <span class="text-sm font-semibold text-slate-300 mt-1 block">{{ $copy->barcode_printed_at ? $copy->barcode_printed_at->format('Y-m-d H:i') : 'Never' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Associated Title Details -->
                @if($copy->book)
                    <div class="dark-card rounded-2xl p-6 shadow-sm border border-slate-700/40">
                        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4">Book Metadata</h3>
                        <div class="space-y-4">
                            <div>
                                <span class="text-xs text-slate-500 block">Title</span>
                                <a href="{{ route('books.show', $copy->book) }}" class="text-base font-bold text-indigo-400 hover:text-indigo-300 mt-1 block transition-colors">{{ $copy->book->title }}</a>
                            </div>
                            <div>
                                <span class="text-xs text-slate-500 block">Author</span>
                                <span class="text-sm font-semibold text-slate-300 mt-1 block">{{ $copy->book->author->name ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-slate-500 block">Category</span>
                                <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-slate-700/50 text-slate-300 border border-slate-650 inline-block mt-1.5">{{ $copy->book->category->name ?? 'General' }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-slate-500 block">ISBN</span>
                                <span class="text-sm font-mono text-slate-450 mt-1 block">{{ $copy->book->isbn ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-amber-950/20 border border-amber-500/20 rounded-2xl p-5 text-amber-300 shadow">
                        <p class="text-sm font-semibold">Unassigned Barcode Sticker</p>
                        <p class="text-xs text-slate-400 mt-1.5">This physical barcode tag has been generated and printed, but has not yet been linked to any book catalog title. Use the "Add Book Copy" registry to link this sticker.</p>
                    </div>
                @endif
            </div>

            <!-- Circulation History Logs -->
            <div class="lg:col-span-2">
                <div class="dark-card rounded-2xl p-6 shadow-sm border border-slate-700/40 h-full">
                    <h3 class="text-base font-bold text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Circulation History for Copy
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-slate-400 text-xs uppercase tracking-wider border-b border-slate-700/50">
                                    <th class="pb-3 font-medium">Borrower</th>
                                    <th class="pb-3 font-medium">Issue Date</th>
                                    <th class="pb-3 font-medium">Due Date</th>
                                    <th class="pb-3 font-medium">Returned Date</th>
                                    <th class="pb-3 font-medium">Status</th>
                                    <th class="pb-3 font-medium text-right">Fine</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-slate-700/30">
                                @forelse($history as $transaction)
                                    <tr>
                                        <td class="py-3.5 text-slate-200 font-semibold">
                                            <a href="{{ route('members.show', $transaction->member) }}" class="hover:text-indigo-400 transition-colors">{{ $transaction->member->name }}</a>
                                        </td>
                                        <td class="py-3.5 text-slate-400">{{ $transaction->issue_date->format('Y-m-d') }}</td>
                                        <td class="py-3.5 text-slate-400">{{ $transaction->due_date->format('Y-m-d') }}</td>
                                        <td class="py-3.5 text-slate-400">{{ $transaction->return_date ? $transaction->return_date->format('Y-m-d') : '-' }}</td>
                                        <td class="py-3.5">
                                            @php
                                                $badgeClass = match($transaction->status) {
                                                    'Issued' => 'bg-blue-500/10 text-blue-400 border border-blue-500/20',
                                                    'Returned' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
                                                    'Overdue' => 'bg-rose-500/10 text-rose-400 border border-rose-500/20',
                                                    'Lost' => 'bg-slate-500/15 text-slate-400 border border-slate-700',
                                                    default => 'bg-slate-700/50 text-slate-300 border border-slate-650',
                                                };
                                            @endphp
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $badgeClass }}">
                                                {{ $transaction->status }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 text-right font-mono font-bold text-slate-400">
                                            ₹{{ number_format($transaction->fine_amount, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-6 text-center text-slate-500 font-medium">This physical copy has no previous circulation logs.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @elseif($barcodeId)
        <div class="bg-slate-800/40 border border-slate-700 rounded-3xl p-8 text-center max-w-2xl">
            <svg class="w-12 h-12 text-slate-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <h4 class="text-white font-bold mb-1">No scan results found</h4>
            <p class="text-slate-400 text-sm">Please verify the sticker ID value and make sure it has been registered in the database.</p>
        </div>
    @else
        <!-- Scanning Prompt Standby Card -->
        <div class="bg-slate-800/20 border border-dashed border-slate-700 rounded-3xl p-12 text-center max-w-2xl">
            <div class="bg-indigo-600/10 p-4 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4 border border-indigo-500/20 animate-bounce">
                <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <h4 class="text-white font-bold text-lg mb-1">Ready for Scans</h4>
            <p class="text-slate-400 text-sm max-w-md mx-auto">Please scanner-scan a physical label sticker or manually input a code above to view details, catalog titles, and checkout history.</p>
        </div>
    @endif

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const scanInput = document.getElementById('barcode_id');
            if (scanInput) {
                // Ensure barcode input is focused on load
                scanInput.focus();

                // Select text for quick overwrite
                scanInput.select();

                // Prevent cursor loss
                scanInput.addEventListener('blur', () => {
                    setTimeout(() => scanInput.focus(), 100);
                });
            }
        });
    </script>
    @endpush
@endsection

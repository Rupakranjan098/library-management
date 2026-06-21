@extends('layouts.admin')

@section('title', $book->title . ' - Book Details')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Book Details</h1>
            <p class="text-slate-400 mt-1 text-sm">Detailed information and barcode for "{{ $book->title }}".</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('books.edit', $book) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                Edit Book
            </a>
            <a href="{{ route('books.index') }}" class="text-slate-400 hover:text-white transition-colors flex items-center bg-slate-800/50 hover:bg-slate-700/50 px-4 py-2 rounded-lg border border-slate-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Books
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details Card -->
        <div class="lg:col-span-2 space-y-6">
            <div class="dark-card rounded-2xl p-6 shadow-lg border border-slate-700/40">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Book Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <span class="text-xs text-slate-500 uppercase tracking-wider block">Title</span>
                        <span class="text-base font-semibold text-slate-200 block mt-1">{{ $book->title }}</span>
                    </div>

                    <div>
                        <span class="text-xs text-slate-500 uppercase tracking-wider block">Author</span>
                        <span class="text-base font-semibold text-slate-200 block mt-1">{{ $book->author->name ?? 'N/A' }}</span>
                    </div>

                    <div>
                        <span class="text-xs text-slate-500 uppercase tracking-wider block">Category</span>
                        <span class="text-base font-semibold text-slate-200 block mt-1">
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-semibold bg-slate-700/50 text-slate-300 border border-slate-600">
                                {{ $book->category->name ?? 'General' }}
                            </span>
                        </span>
                    </div>

                    <div>
                        <span class="text-xs text-slate-500 uppercase tracking-wider block">ISBN</span>
                        <span class="text-base font-semibold text-slate-400 font-mono block mt-1">{{ $book->isbn ?? 'N/A' }}</span>
                    </div>

                    <div>
                        <span class="text-xs text-slate-500 uppercase tracking-wider block">Total Copies</span>
                        <span id="display-total-copies" class="text-base font-semibold text-emerald-400 block mt-1">{{ $book->copies->where('status', '!=', 'Retired')->count() }}</span>
                    </div>

                    <div>
                        <span class="text-xs text-slate-500 uppercase tracking-wider block">Available Copies</span>
                        <span id="display-available-copies" class="text-base font-semibold text-indigo-400 block mt-1">{{ $book->copies->where('status', 'Available')->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Borrow History Card -->
            <div class="dark-card rounded-2xl p-6 shadow-lg border border-slate-700/40">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Circulation History
                    </h3>
                    <div class="flex items-center space-x-2">
                        <span class="text-xs text-slate-500 font-medium">Filter Status:</span>
                        <select onchange="filterHistory(this.value)" class="bg-[#0f172a] border border-slate-700 rounded-lg py-1 px-2.5 text-xs text-slate-300 focus:outline-none focus:border-indigo-500">
                            <option value="all">All</option>
                            <option value="Issued">Issued</option>
                            <option value="Returned">Returned</option>
                            <option value="Overdue">Overdue</option>
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse" id="historyTable">
                        <thead>
                            <tr class="text-slate-400 text-xs uppercase tracking-wider border-b border-slate-700/50">
                                <th class="pb-3 font-medium">Member</th>
                                <th class="pb-3 font-medium">Issued Date</th>
                                <th class="pb-3 font-medium">Due Date</th>
                                <th class="pb-3 font-medium">Returned Date</th>
                                <th class="pb-3 font-medium">Status</th>
                                <th class="pb-3 font-medium text-right">Fine</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-700/30">
                            @forelse($book->transactions as $transaction)
                                <tr class="history-row" data-status="{{ $transaction->status }}">
                                    <td class="py-3 text-slate-300 font-medium">
                                        <a href="{{ route('members.show', $transaction->member) }}" class="hover:text-indigo-400 transition-colors">{{ $transaction->member->name }}</a>
                                    </td>
                                    <td class="py-3 text-slate-400">{{ $transaction->issue_date->format('Y-m-d') }}</td>
                                    <td class="py-3 text-slate-400">{{ $transaction->due_date->format('Y-m-d') }}</td>
                                    <td class="py-3 text-slate-400">{{ $transaction->return_date ? $transaction->return_date->format('Y-m-d') : '-' }}</td>
                                    <td class="py-3">
                                        @php
                                            $badgeClass = match($transaction->status) {
                                                'Issued' => 'bg-blue-500/10 text-blue-400 border border-blue-500/20',
                                                'Returned' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
                                                'Overdue' => 'bg-rose-500/10 text-rose-400 border border-rose-500/20',
                                                'Lost' => 'bg-slate-500/15 text-slate-400 border border-slate-700',
                                                default => 'bg-slate-700/50 text-slate-300 border border-slate-600',
                                            };
                                        @endphp
                                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $badgeClass }}">
                                            {{ $transaction->status }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-right font-mono font-bold text-slate-400">
                                        ₹{{ number_format($transaction->fine_amount, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr class="empty-row">
                                    <td colspan="6" class="py-4 text-center text-slate-500">No circulation records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @push('scripts')
            <script>
                function filterHistory(status) {
                    const rows = document.querySelectorAll('#historyTable .history-row');
                    let visibleCount = 0;
                    
                    rows.forEach(row => {
                        if (status === 'all' || row.getAttribute('data-status') === status) {
                            row.style.display = '';
                            visibleCount++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Manage empty state if all rows filtered out
                    let emptyMsgRow = document.getElementById('historyEmptyMsg');
                    if (visibleCount === 0 && rows.length > 0) {
                        if (!emptyMsgRow) {
                            emptyMsgRow = document.createElement('tr');
                            emptyMsgRow.id = 'historyEmptyMsg';
                            emptyMsgRow.innerHTML = `<td colspan="6" class="py-4 text-center text-slate-500">No transactions match the selected status.</td>`;
                            document.querySelector('#historyTable tbody').appendChild(emptyMsgRow);
                        }
                    } else if (emptyMsgRow) {
                        emptyMsgRow.remove();
                    }
                }
            </script>
            @endpush
        </div>

        <!-- Sidebar Copies Card -->
        <div class="space-y-6">
            <!-- Add Copies via Scan Card -->
            <div class="dark-card rounded-2xl p-6 shadow-lg border border-slate-700/40 bg-indigo-950/10">
                <h3 class="text-base font-bold text-white mb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    Add Copies via Scan
                </h3>
                <p class="text-xs text-slate-400 mb-4">Scan pre-printed unassigned barcode stickers to link them to this book title.</p>
                
                <div class="space-y-4">
                    <div>
                        <input type="text" id="scan_copy_barcode" class="w-full bg-slate-900 border border-slate-700 rounded-xl py-2.5 px-4 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-mono tracking-wider text-center" placeholder="SCAN BARCODE TO LINK...">
                        <div id="scan-error-msg" class="text-rose-455 text-xs mt-1.5 hidden font-medium bg-rose-500/10 border border-rose-500/20 p-2.5 rounded-lg"></div>
                        <div id="scan-success-msg" class="text-emerald-400 text-xs mt-1.5 hidden font-medium bg-emerald-500/10 border border-emerald-500/20 p-2.5 rounded-lg"></div>
                    </div>

                    <!-- Live Session Stats -->
                    <div id="session-stats-container" class="bg-slate-800/40 rounded-xl p-3 border border-slate-700/40 hidden">
                        <div class="flex justify-between items-center text-xs mb-2">
                            <span class="text-slate-400">Session Progress</span>
                            <span id="linked-counter" class="font-bold text-indigo-300">0 copies linked</span>
                        </div>
                        <div class="max-h-[120px] overflow-y-auto space-y-1.5 pr-1" id="linked-session-barcodes">
                            <!-- List of barcodes scanned this session -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="dark-card rounded-2xl p-6 shadow-lg border border-slate-700/40">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2zM9 16V8m3 8V8m3 8V8m-6 0h6m-6 8h6"></path></svg>
                        Physical Copies
                    </div>
                    <span id="display-sidebar-total-badge" class="text-xs font-semibold px-2.5 py-0.5 bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 rounded-md">
                        {{ $book->copies->where('status', '!=', 'Retired')->count() }} Total
                    </span>
                </h3>

                <div id="copies-list-container" class="space-y-3 max-h-[380px] overflow-y-auto pr-1">
                    @forelse($book->copies as $copy)
                        <div class="bg-slate-800/30 border border-slate-700/40 rounded-xl p-3 flex items-center justify-between">
                            <div>
                                <span class="font-mono text-sm font-bold text-slate-200 block">{{ $copy->barcode_id }}</span>
                                <span class="text-[10px] text-slate-500 block mt-0.5">Printed: {{ $copy->barcode_printed_at ? $copy->barcode_printed_at->format('Y-m-d') : 'Never' }}</span>
                            </div>
                            <div class="text-right">
                                @php
                                    $statusClass = match($copy->status) {
                                        'Available' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
                                        'Issued' => 'bg-blue-500/10 text-blue-400 border border-blue-500/20',
                                        'Overdue' => 'bg-rose-500/10 text-rose-400 border border-rose-500/20',
                                        'Retired' => 'bg-slate-500/10 text-slate-400 border border-slate-700',
                                        default => 'bg-slate-700/50 text-slate-300 border border-slate-600',
                                    };
                                @endphp
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider {{ $statusClass }} block text-center">
                                    {{ $copy->status }}
                                </span>
                                <a href="{{ route('books.print-barcodes', ['ids' => $copy->id]) }}" class="text-[10px] text-indigo-400 hover:text-indigo-300 font-semibold mt-1 inline-block transition-colors" title="Print Barcode label">Print Label &rarr;</a>
                            </div>
                        </div>
                    @empty
                        <p id="no-copies-message" class="text-xs text-slate-500 text-center py-4">No copies registered for this book title.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const scanInput = document.getElementById('scan_copy_barcode');
            const errorMsg = document.getElementById('scan-error-msg');
            const successMsg = document.getElementById('scan-success-msg');
            const sessionStats = document.getElementById('session-stats-container');
            const linkedCounter = document.getElementById('linked-counter');
            const sessionList = document.getElementById('linked-session-barcodes');
            
            const copiesList = document.getElementById('copies-list-container');
            const noCopiesMsg = document.getElementById('no-copies-message');
            
            const displayTotal = document.getElementById('display-total-copies');
            const displayAvailable = document.getElementById('display-available-copies');
            const sidebarTotalBadge = document.getElementById('display-sidebar-total-badge');

            let linkedCount = 0;

            if (scanInput) {
                // Ensure barcode input is focused on load
                scanInput.focus();

                // Prevent cursor loss
                scanInput.addEventListener('blur', () => {
                    setTimeout(() => scanInput.focus(), 100);
                });

                scanInput.addEventListener('keydown', async (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const barcodeId = scanInput.value.trim();
                        if (!barcodeId) return;

                        // Clear messages
                        errorMsg.classList.add('hidden');
                        successMsg.classList.add('hidden');

                        try {
                            const response = await fetch("{{ route('books.link-barcode', $book) }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ barcode_id: barcodeId })
                            });

                            const data = await response.json();

                            if (response.ok && data.success) {
                                // Success!
                                successMsg.innerText = `Successfully linked barcode: ${data.barcode_id}`;
                                successMsg.classList.remove('hidden');

                                // Increment session counter
                                linkedCount++;
                                linkedCounter.innerText = `${linkedCount} copies linked`;

                                // Show session stats
                                sessionStats.classList.remove('hidden');

                                // Append to session list
                                const sessionItem = document.createElement('div');
                                sessionItem.className = 'text-xs font-mono text-emerald-400 flex items-center justify-between border-b border-slate-800 pb-1';
                                sessionItem.innerHTML = `<span>✓ ${data.barcode_id}</span><span class="text-[9px] bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-1 rounded uppercase font-bold">Linked</span>`;
                                sessionList.prepend(sessionItem);

                                // Update Page counts
                                if (displayTotal) displayTotal.innerText = data.total_copies;
                                if (displayAvailable) displayAvailable.innerText = data.available_copies;
                                if (sidebarTotalBadge) sidebarTotalBadge.innerText = `${data.total_copies} Total`;

                                // Prepend to Copies List in sidebar
                                if (noCopiesMsg) noCopiesMsg.remove();
                                
                                const newCopyItem = document.createElement('div');
                                newCopyItem.className = 'bg-slate-800/30 border border-slate-700/40 rounded-xl p-3 flex items-center justify-between transition-all duration-500 border-indigo-500/40';
                                newCopyItem.innerHTML = `
                                    <div>
                                        <span class="font-mono text-sm font-bold text-slate-200 block">${data.barcode_id}</span>
                                        <span class="text-[10px] text-slate-500 block mt-0.5">Printed: Just Now</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 block text-center">
                                            Available
                                        </span>
                                        <a href="/books/print-barcodes?ids=${data.barcode_id}" class="text-[10px] text-indigo-400 hover:text-indigo-300 font-semibold mt-1 inline-block transition-colors" title="Print Barcode label">Print Label &rarr;</a>
                                    </div>
                                `;
                                copiesList.prepend(newCopyItem);

                                // Clear input
                                scanInput.value = '';
                                scanInput.focus();
                            } else {
                                // Error
                                errorMsg.innerText = data.message || 'An error occurred linking this barcode.';
                                errorMsg.classList.remove('hidden');
                                scanInput.value = '';
                                scanInput.focus();
                            }
                        } catch (err) {
                            console.error(err);
                            errorMsg.innerText = 'Network error linking barcode. Please try again.';
                            errorMsg.classList.remove('hidden');
                            scanInput.value = '';
                            scanInput.focus();
                        }
                    }
                });
            }
        });
    </script>
    @endpush
@endsection

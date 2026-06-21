@extends('layouts.admin')

@section('title', $member->name . ' - Member Profile')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Member Profile</h1>
            <p class="text-slate-400 mt-1 text-sm font-medium">Detailed information and borrowing records for {{ $member->name }}.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('members.edit', $member) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                Edit Member
            </a>
            <a href="{{ route('members.index') }}" class="text-slate-400 hover:text-white transition-colors flex items-center bg-slate-800/50 hover:bg-slate-700/50 px-4 py-2 rounded-lg border border-slate-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Members
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="dark-card rounded-2xl p-6 shadow-lg border border-slate-700/40 text-center">
                <div class="w-24 h-24 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white text-3xl font-extrabold mx-auto mb-4 shadow-md">
                    {{ substr($member->name, 0, 1) }}
                </div>
                <h3 class="text-xl font-bold text-white mb-1">{{ $member->name }}</h3>
                <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                    {{ $member->member_type ?? 'student' }}
                </span>

                <div class="mt-6 space-y-4 text-left border-t border-slate-700/50 pt-6">
                    <div>
                        <span class="text-xs text-slate-500 uppercase tracking-wider block">Email Address</span>
                        <span class="text-sm font-semibold text-slate-300 block mt-0.5">{{ $member->email }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-slate-500 uppercase tracking-wider block">Phone Number</span>
                        <span class="text-sm font-semibold text-slate-300 block mt-0.5">{{ $member->phone ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-slate-500 uppercase tracking-wider block">Membership Date</span>
                        <span class="text-sm font-semibold text-slate-300 block mt-0.5">{{ $member->membership_date ? \Carbon\Carbon::parse($member->membership_date)->format('Y-m-d') : 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- History Card -->
        <div class="lg:col-span-2 space-y-6">
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
                                <th class="pb-3 font-medium">Book</th>
                                <th class="pb-3 font-medium">Barcode</th>
                                <th class="pb-3 font-medium">Issued Date</th>
                                <th class="pb-3 font-medium">Due Date</th>
                                <th class="pb-3 font-medium">Returned Date</th>
                                <th class="pb-3 font-medium">Status</th>
                                <th class="pb-3 font-medium text-right">Fine</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-700/30">
                            @forelse($member->transactions as $transaction)
                                <tr class="history-row" data-status="{{ $transaction->status }}">
                                    <td class="py-3 text-slate-300 font-medium">
                                        <a href="{{ route('books.show', $transaction->book) }}" class="hover:text-indigo-400 transition-colors">{{ $transaction->book->title }}</a>
                                    </td>
                                    <td class="py-3 text-slate-400 font-mono text-xs">{{ $transaction->book->barcode_id }}</td>
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
                                    <td colspan="7" class="py-4 text-center text-slate-500">No checkout history records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
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
                    emptyMsgRow.innerHTML = `<td colspan="7" class="py-4 text-center text-slate-500">No transactions match the selected status.</td>`;
                    document.querySelector('#historyTable tbody').appendChild(emptyMsgRow);
                }
            } else if (emptyMsgRow) {
                emptyMsgRow.remove();
            }
        }
    </script>
    @endpush
@endsection

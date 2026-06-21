@extends('layouts.admin')

@section('title', 'Manage Members - Library Management')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Members</h1>
            <p class="text-slate-400 mt-1 text-sm">Manage the library's registered members.</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="bulkDeleteMembers()" id="bulkDeleteBtn" class="hidden bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 rounded-lg font-medium shadow-[0_4px_15px_rgba(225,29,72,0.3)] transition-colors flex items-center" style="background-color: #e11d48;">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Delete Selected
            </button>
            <a href="{{ route('members.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium shadow-[0_4px_15px_rgba(59,130,246,0.3)] transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                Add New Member
            </a>
        </div>
    </div>

    <!-- Data Table -->
    <div class="dark-card rounded-2xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-slate-700/50 flex justify-between items-center bg-slate-800/30">
            <form action="{{ route('members.index') }}" method="GET" class="relative w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" class="w-full bg-[#0f172a] border border-slate-700 rounded-lg py-1.5 pl-9 pr-4 text-sm text-slate-300 placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" placeholder="Search members...">
            </form>
            <div class="text-slate-400 text-sm">
                Showing {{ $members->count() }} members
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-slate-400 text-xs uppercase tracking-wider bg-slate-800/20 border-b border-slate-700/80">
                        <th class="px-6 py-4 font-medium w-12 text-center">
                            <input type="checkbox" id="selectAllCheckbox" class="rounded bg-slate-800 border-slate-700 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-4 font-medium">Member Name</th>
                        <th class="px-6 py-4 font-medium">Contact</th>
                        <th class="px-6 py-4 font-medium">Expiry Date</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                        <th class="px-6 py-4 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-700/50">
                    @forelse($members as $member)
                    <tr class="hover:bg-slate-800/30 transition-colors group">
                        <td class="px-6 py-4 w-12 text-center">
                            <input type="checkbox" name="member_ids[]" value="{{ $member->id }}" class="member-select-checkbox rounded bg-slate-800 border-slate-700 text-blue-600 focus:ring-blue-500">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-blue-500/20 flex flex-col items-center justify-center text-blue-400 mr-4 border border-blue-500/30 shrink-0 font-bold">
                                    {{ substr($member->name, 0, 1) }}
                                </div>
                                <div>
                                    <a href="{{ route('members.show', $member) }}" class="font-bold text-slate-200 hover:text-blue-400 transition-colors">{{ $member->name }}</a>
                                    <div class="text-[10px] text-slate-500 uppercase tracking-wide">ID: #{{ str_pad($member->id, 5, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-slate-300 text-xs mb-1 flex items-center">
                                <svg class="w-3.5 h-3.5 text-slate-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                {{ $member->email }}
                            </div>
                            <div class="text-slate-400 text-xs flex items-center">
                                <svg class="w-3.5 h-3.5 text-slate-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                {{ $member->phone }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-400 text-xs font-medium">
                            {{ \Carbon\Carbon::parse($member->membership_expiry)->format('d M, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            @if(\Carbon\Carbon::parse($member->membership_expiry)->isPast())
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-semibold bg-red-500/10 text-red-400 border border-red-500/20">Expired</span>
                            @else
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Active</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('members.show', $member) }}" class="p-2 text-slate-400 hover:text-blue-400 hover:bg-blue-400/10 rounded transition-colors" title="View Profile">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="{{ route('members.edit', $member) }}" class="p-2 text-slate-400 hover:text-blue-400 hover:bg-blue-400/10 rounded transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <form action="{{ route('members.destroy', $member) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this member?');">
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
                        <td colspan="5" class="py-8 text-center text-slate-500">No members registered yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <!-- Bulk Delete Form -->
    <form id="bulkDeleteForm" action="{{ route('members.bulk-delete') }}" method="POST" class="hidden">
        @csrf
        <div id="bulkDeleteInputs"></div>
    </form>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAll = document.getElementById('selectAllCheckbox');
            const checkboxes = document.querySelectorAll('.member-select-checkbox');
            const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

            function toggleBulkDeleteButton() {
                const checkedCount = document.querySelectorAll('.member-select-checkbox:checked').length;
                if (checkedCount > 0) {
                    bulkDeleteBtn.classList.remove('hidden');
                } else {
                    bulkDeleteBtn.classList.add('hidden');
                }
            }

            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    checkboxes.forEach(cb => {
                        cb.checked = this.checked;
                    });
                    toggleBulkDeleteButton();
                });
            }

            checkboxes.forEach(cb => {
                cb.addEventListener('change', function () {
                    if (!this.checked && selectAll) {
                        selectAll.checked = false;
                    } else if (selectAll && document.querySelectorAll('.member-select-checkbox:checked').length === checkboxes.length) {
                        selectAll.checked = true;
                    }
                    toggleBulkDeleteButton();
                });
            });
        });

        function bulkDeleteMembers() {
            const checked = document.querySelectorAll('.member-select-checkbox:checked');
            if (checked.length === 0) {
                alert('Please select at least one member to delete.');
                return;
            }

            if (confirm(`Are you sure you want to delete the ${checked.length} selected members? This action cannot be undone.`)) {
                const form = document.getElementById('bulkDeleteForm');
                const inputsContainer = document.getElementById('bulkDeleteInputs');
                inputsContainer.innerHTML = '';

                checked.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = cb.value;
                    inputsContainer.appendChild(input);
                });

                form.submit();
            }
        }
    </script>
    @endpush
@endsection

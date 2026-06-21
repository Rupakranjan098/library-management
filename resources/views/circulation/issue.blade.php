@extends('layouts.admin')

@section('title', 'Issue Book - Library Management')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white tracking-tight">Issue Book</h1>
                <p class="text-slate-400 mt-1 text-sm">Register a new checkout transaction by scanning the book barcode.</p>
            </div>
            <a href="{{ route('circulation.index') }}" class="bg-[#1e293b] hover:bg-[#334155] border border-slate-700 text-slate-300 px-4 py-2 rounded-lg font-medium transition-colors text-sm">
                Back to Circulation
            </a>
        </div>

        <div class="dark-card rounded-2xl p-6 shadow-lg">
            <form action="{{ route('circulation.issue.post') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="member_id" class="block text-sm font-medium text-slate-300 mb-2">Select Member</label>
                    <select name="member_id" id="member_id" required class="w-full bg-slate-800/50 border border-slate-700 rounded-xl py-3 px-4 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <option value="" disabled selected>-- Select Member --</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                {{ $member->name }} ({{ $member->email }}) [{{ ucfirst($member->member_type ?? 'student') }}]
                            </option>
                        @endforeach
                    </select>
                    @error('member_id')
                        <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="border-t border-slate-700/50 pt-5">
                    <x-scan-barcode 
                        id="barcode_id" 
                        name="barcode_id" 
                        label="Scan Book Barcode" 
                        placeholder="Scan or type barcode (e.g., LIB-000001)..." 
                        value="{{ old('barcode_id') }}"
                    />
                    @error('barcode_id')
                        <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="border-t border-slate-700/50 pt-5 flex justify-end space-x-3">
                    <a href="{{ route('circulation.index') }}" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-sm font-medium transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-medium transition-colors shadow-lg shadow-indigo-600/30">
                        Issue Book
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

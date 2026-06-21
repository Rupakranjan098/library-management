@extends('layouts.admin')

@section('title', 'Edit Member - Library Management')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Edit Member</h1>
            <p class="text-slate-400 mt-1 text-sm">Update member details.</p>
        </div>
        <a href="{{ route('members.index') }}" class="text-slate-400 hover:text-white transition-colors flex items-center bg-slate-800/50 hover:bg-slate-700/50 px-4 py-2 rounded-xl border border-slate-700">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Members
        </a>
    </div>

    <div class="dark-card rounded-3xl p-8 max-w-3xl">
        <form action="{{ route('members.update', $member) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Full Name</label>
                    <input type="text" name="name" value="{{ $member->name }}" required class="w-full bg-slate-800/50 border border-slate-700 rounded-xl py-3 px-4 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ $member->email }}" required class="w-full bg-slate-800/50 border border-slate-700 rounded-xl py-3 px-4 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Phone Number</label>
                    <input type="text" name="phone" value="{{ $member->phone }}" class="w-full bg-slate-800/50 border border-slate-700 rounded-xl py-3 px-4 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Membership Date</label>
                    <input type="text" name="membership_date" value="{{ \Carbon\Carbon::parse($member->membership_date)->format('Y-m-d') }}" required class="datepicker w-full bg-slate-800/50 border border-slate-700 rounded-xl py-3 px-4 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="YYYY-MM-DD">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-700/50 flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-medium shadow-[0_4px_15px_rgba(79,70,229,0.3)] transition-colors">
                    Update Member
                </button>
            </div>
        </form>
    </div>
@endsection

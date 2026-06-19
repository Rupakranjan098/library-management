@extends('layouts.admin')

@section('title', 'Settings - Library Management')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Settings</h1>
            <p class="text-slate-400 mt-1 text-sm">Configure your library system preferences.</p>
        </div>
        <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-medium shadow-[0_4px_15px_rgba(79,70,229,0.3)] transition-colors flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
            Save Settings
        </button>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        
        <!-- Settings Sidebar -->
        <div class="w-full lg:w-64 shrink-0 space-y-1">
            <a href="#" class="flex items-center px-4 py-3 bg-indigo-500/10 text-indigo-400 rounded-xl transition-colors group">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span class="font-medium text-sm">General</span>
            </a>
            <a href="#" class="flex items-center px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-800/50 rounded-xl transition-colors group">
                <svg class="w-5 h-5 mr-3 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                <span class="font-medium text-sm">Library Information</span>
            </a>
            <a href="#" class="flex items-center px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-800/50 rounded-xl transition-colors group">
                <svg class="w-5 h-5 mr-3 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-medium text-sm">Fine & Fees</span>
            </a>
            <a href="#" class="flex items-center px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-800/50 rounded-xl transition-colors group">
                <svg class="w-5 h-5 mr-3 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                <span class="font-medium text-sm">Notifications</span>
            </a>
            <a href="#" class="flex items-center px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-800/50 rounded-xl transition-colors group">
                <svg class="w-5 h-5 mr-3 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="font-medium text-sm">User Management</span>
            </a>
            <a href="#" class="flex items-center px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-800/50 rounded-xl transition-colors group">
                <svg class="w-5 h-5 mr-3 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                <span class="font-medium text-sm">Backup & Restore</span>
            </a>
            <a href="#" class="flex items-center px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-800/50 rounded-xl transition-colors group">
                <svg class="w-5 h-5 mr-3 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                <span class="font-medium text-sm">Security</span>
            </a>
            <a href="#" class="flex items-center px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-800/50 rounded-xl transition-colors group">
                <svg class="w-5 h-5 mr-3 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span class="font-medium text-sm">System Logs</span>
            </a>
        </div>

        <!-- Main Settings Content -->
        <div class="flex-1 space-y-6">
            
            <!-- System Configuration -->
            <div class="dark-card rounded-3xl p-8">
                <h3 class="text-lg font-bold text-white mb-1">System Configuration</h3>
                <p class="text-sm text-slate-400 mb-8">Manage the core settings of your library system.</p>

                <div class="flex flex-col xl:flex-row gap-8">
                    <!-- 3D Image -->
                    <div class="w-full xl:w-1/3 flex justify-center items-center">
                        <div class="relative w-full max-w-[280px] aspect-square bg-gradient-to-b from-indigo-500/5 to-transparent rounded-full flex items-center justify-center p-6">
                            <img src="{{ asset('images/system_config.png') }}" alt="System Configuration" class="w-full h-full object-contain rounded-3xl drop-shadow-2xl hover:scale-105 transition-transform duration-500">
                        </div>
                    </div>

                    <!-- Form -->
                    <div class="flex-1 space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Library Name</label>
                                <input type="text" class="w-full bg-slate-800/50 border border-slate-700 rounded-xl py-2.5 px-4 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" value="Laravel Library">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Default Language</label>
                                <select class="w-full bg-slate-800/50 border border-slate-700 rounded-xl py-2.5 px-4 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 appearance-none">
                                    <option>English</option>
                                    <option>Spanish</option>
                                    <option>French</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Date Format</label>
                                <select class="w-full bg-slate-800/50 border border-slate-700 rounded-xl py-2.5 px-4 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 appearance-none">
                                    <option>May 24, 2024 (MMM DD, YYYY)</option>
                                    <option>24/05/2024 (DD/MM/YYYY)</option>
                                    <option>2024-05-24 (YYYY-MM-DD)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Time Zone</label>
                                <select class="w-full bg-slate-800/50 border border-slate-700 rounded-xl py-2.5 px-4 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 appearance-none">
                                    <option>(UTC+05:30) Kolkata, India</option>
                                    <option>(UTC+00:00) London, UK</option>
                                    <option>(UTC-05:00) Eastern Time</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Currency</label>
                            <select class="w-full bg-slate-800/50 border border-slate-700 rounded-xl py-2.5 px-4 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 appearance-none">
                                <option>INR (₹)</option>
                                <option>USD ($)</option>
                                <option>EUR (€)</option>
                            </select>
                        </div>

                        <div class="pt-4 border-t border-slate-700/50 flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-white">Enable Maintenance Mode</h4>
                                <p class="text-xs text-slate-400 mt-0.5">System will be offline for regular users.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Two Column Row -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                
                <!-- Borrowing Settings -->
                <div class="dark-card rounded-3xl p-8">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center mr-4 border border-indigo-500/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Borrowing Settings</h3>
                            <p class="text-xs text-slate-400">Configure book borrowing rules and limits.</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="text-sm font-medium text-slate-300 block">Maximum Books per Member</label>
                                <span class="text-[11px] text-slate-500">How many books a member can borrow.</span>
                            </div>
                            <input type="number" value="5" class="w-20 bg-slate-800/50 border border-slate-700 rounded-lg py-2 px-3 text-sm text-center text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="text-sm font-medium text-slate-300 block">Borrowing Period (Days)</label>
                                <span class="text-[11px] text-slate-500">Default number of days for borrowing.</span>
                            </div>
                            <input type="number" value="14" class="w-20 bg-slate-800/50 border border-slate-700 rounded-lg py-2 px-3 text-sm text-center text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="text-sm font-medium text-slate-300 block">Renewal Period (Days)</label>
                                <span class="text-[11px] text-slate-500">Additional days when a book is renewed.</span>
                            </div>
                            <input type="number" value="7" class="w-20 bg-slate-800/50 border border-slate-700 rounded-lg py-2 px-3 text-sm text-center text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div class="pt-2 flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-slate-300 block">Allow Renewals</h4>
                                <span class="text-[11px] text-slate-500">Allow members to renew borrowed books.</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Fine Settings -->
                <div class="dark-card rounded-3xl p-8">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center mr-4 border border-emerald-500/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Fine Settings</h3>
                            <p class="text-xs text-slate-400">Configure fine rules for overdue books.</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="text-sm font-medium text-slate-300 block">Fine per Day (₹)</label>
                                <span class="text-[11px] text-slate-500">Fine charged per day for overdue books.</span>
                            </div>
                            <input type="number" value="2.00" class="w-24 bg-slate-800/50 border border-slate-700 rounded-lg py-2 px-3 text-sm text-center text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="text-sm font-medium text-slate-300 block">Maximum Fine (₹)</label>
                                <span class="text-[11px] text-slate-500">Maximum fine limit per book.</span>
                            </div>
                            <input type="number" value="100.00" class="w-24 bg-slate-800/50 border border-slate-700 rounded-lg py-2 px-3 text-sm text-center text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="text-sm font-medium text-slate-300 block">Grace Period (Days)</label>
                                <span class="text-[11px] text-slate-500">No fine will be charged within this period.</span>
                            </div>
                            <input type="number" value="1" class="w-24 bg-slate-800/50 border border-slate-700 rounded-lg py-2 px-3 text-sm text-center text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

            </div>

            <!-- Email Notifications -->
            <div class="dark-card rounded-3xl p-8">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center mr-4 border border-blue-500/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Email Notifications</h3>
                        <p class="text-xs text-slate-400">Manage email notifications sent to members.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <label class="flex items-start space-x-3 cursor-pointer group">
                        <div class="flex items-center h-5">
                            <input type="checkbox" checked class="w-4 h-4 rounded border-slate-700 text-indigo-600 focus:ring-indigo-600 bg-slate-800/50">
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-slate-300 group-hover:text-white transition-colors">New Member Registration</span>
                            <span class="text-[11px] text-slate-500">Send email when a new member is registered.</span>
                        </div>
                    </label>

                    <label class="flex items-start space-x-3 cursor-pointer group">
                        <div class="flex items-center h-5">
                            <input type="checkbox" checked class="w-4 h-4 rounded border-slate-700 text-indigo-600 focus:ring-indigo-600 bg-slate-800/50">
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-slate-300 group-hover:text-white transition-colors">Book Due Reminder</span>
                            <span class="text-[11px] text-slate-500">Send reminder before book due date.</span>
                        </div>
                    </label>

                    <label class="flex items-start space-x-3 cursor-pointer group">
                        <div class="flex items-center h-5">
                            <input type="checkbox" checked class="w-4 h-4 rounded border-slate-700 text-indigo-600 focus:ring-indigo-600 bg-slate-800/50">
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-slate-300 group-hover:text-white transition-colors">Overdue Notices</span>
                            <span class="text-[11px] text-slate-500">Send email for overdue books.</span>
                        </div>
                    </label>
                </div>
            </div>

        </div>
    </div>

@endsection

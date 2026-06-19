@extends('layouts.admin')

@section('title', 'Reports - Library Management')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Reports</h1>
            <p class="text-slate-400 mt-1 text-sm">Generate and view system reports.</p>
        </div>
        <a href="{{ route('reports.export') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-medium shadow-[0_4px_15px_rgba(79,70,229,0.3)] transition-colors flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Export PDF
        </a>
    </div>

    @if(request()->filled('report_type'))
    <div class="mb-6 bg-indigo-500/10 border border-indigo-500/25 text-indigo-200 px-6 py-4 rounded-3xl flex items-center space-x-3 shadow-md">
        <div class="bg-indigo-500/20 p-2 rounded-xl border border-indigo-500/30 text-indigo-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
        </div>
        <div>
            <span class="font-bold text-sm block leading-none mb-1">Report Generated Successfully</span>
            <span class="text-xs text-slate-400">Showing <strong>{{ request('report_type') }}</strong> for date range <strong>{{ request('date_range', 'May 01, 2024 - May 24, 2024') }}</strong>.</span>
        </div>
    </div>
    @endif

    <!-- Top Stats Cards -->
    <!-- Top Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
        <!-- Total Books -->
        <div class="dark-card rounded-3xl p-6 flex items-center">
            <div class="w-14 h-14 rounded-2xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center shrink-0 border border-indigo-500/30">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <div class="ml-5">
                <p class="text-sm font-medium text-slate-400">Total Books</p>
                <div class="flex items-baseline">
                    <h3 class="text-3xl font-bold text-white">{{ number_format($totalBooks) }}</h3>
                </div>
                <div class="text-[11px] font-medium text-emerald-400 flex items-center mt-1">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    {{ $newBooksThisMonth }} this month
                </div>
            </div>
        </div>

        <!-- Total Members -->
        <div class="dark-card rounded-3xl p-6 flex items-center">
            <div class="w-14 h-14 rounded-2xl bg-blue-500/20 text-blue-400 flex items-center justify-center shrink-0 border border-blue-500/30">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div class="ml-5">
                <p class="text-sm font-medium text-slate-400">Total Members</p>
                <div class="flex items-baseline">
                    <h3 class="text-3xl font-bold text-white">{{ number_format($totalMembers) }}</h3>
                </div>
                <div class="text-[11px] font-medium text-emerald-400 flex items-center mt-1">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    {{ $newMembersThisMonth }} this month
                </div>
            </div>
        </div>

        <!-- Books Borrowed -->
        <div class="dark-card rounded-3xl p-6 flex items-center">
            <div class="w-14 h-14 rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/30">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
            </div>
            <div class="ml-5">
                <p class="text-sm font-medium text-slate-400">Books Borrowed</p>
                <div class="flex items-baseline">
                    <h3 class="text-3xl font-bold text-white">{{ number_format($booksBorrowed) }}</h3>
                </div>
                <div class="text-[11px] font-medium text-emerald-400 flex items-center mt-1">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    {{ $borrowedThisMonth }} this month
                </div>
            </div>
        </div>

        <!-- Overdue Books -->
        <div class="dark-card rounded-3xl p-6 flex items-center">
            <div class="w-14 h-14 rounded-2xl bg-orange-500/20 text-orange-400 flex items-center justify-center shrink-0 border border-orange-500/30">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="ml-5">
                <p class="text-sm font-medium text-slate-400">Overdue Books</p>
                <div class="flex items-baseline">
                    <h3 class="text-3xl font-bold text-white">{{ number_format($overdueBooks) }}</h3>
                </div>
                <div class="text-[11px] font-medium text-red-400 flex items-center mt-1">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                    {{ $overdueThisMonth }} this month
                </div>
            </div>
        </div>

        <!-- Total Fines -->
        <div class="dark-card rounded-3xl p-6 flex items-center">
            <div class="w-14 h-14 rounded-2xl bg-amber-500/20 text-amber-400 flex items-center justify-center shrink-0 border border-amber-500/30">
                <span class="text-2xl font-bold">₹</span>
            </div>
            <div class="ml-5">
                <p class="text-sm font-medium text-slate-400">Total Fines</p>
                <div class="flex items-baseline">
                    <h3 class="text-3xl font-bold text-white">₹{{ number_format($totalFines) }}</h3>
                </div>
                <div class="text-[11px] font-medium text-slate-500 flex items-center mt-1">
                    Accumulated fines
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Bar Form -->
    <form action="{{ route('reports.index') }}" method="GET" class="flex flex-col md:flex-row items-center justify-between gap-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1 ml-1">Report Type</label>
                <select name="report_type" class="w-full md:w-48 bg-slate-800/50 border border-slate-700 rounded-xl py-2 px-4 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 appearance-none cursor-pointer">
                    <option value="All Reports" {{ request('report_type') == 'All Reports' ? 'selected' : '' }}>All Reports</option>
                    <option value="Borrowing History" {{ request('report_type') == 'Borrowing History' ? 'selected' : '' }}>Borrowing History</option>
                    <option value="Inventory Status" {{ request('report_type') == 'Inventory Status' ? 'selected' : '' }}>Inventory Status</option>
                    <option value="Member Activity" {{ request('report_type') == 'Member Activity' ? 'selected' : '' }}>Member Activity</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1 ml-1">Date Range</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <input type="text" name="date_range" value="{{ request('date_range', 'May 01, 2024 - May 24, 2024') }}" class="w-full md:w-64 bg-slate-800/50 border border-slate-700 rounded-xl py-2 pl-9 pr-4 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>
            </div>
        </div>
        <div class="w-full md:w-auto mt-4 md:mt-0 self-end">
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium shadow-[0_4px_15px_rgba(79,70,229,0.3)] transition-colors flex items-center justify-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Generate Report
            </button>
        </div>
    </form>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Line Chart -->
        <div class="dark-card rounded-3xl p-6 lg:col-span-2">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-white">Books Borrowed Over Time</h3>
                <select class="bg-slate-800/50 border border-slate-700 rounded-lg py-1 px-3 text-xs text-slate-300 focus:outline-none appearance-none">
                    <option>This Month</option>
                    <option>Last Month</option>
                    <option>This Year</option>
                </select>
            </div>
            <div class="h-64 relative">
                <canvas id="borrowingsChart"></canvas>
            </div>
        </div>

        <!-- Donut Chart -->
        <div class="dark-card rounded-3xl p-6">
            <h3 class="text-lg font-bold text-white mb-6">Books by Category</h3>
            <div class="h-48 relative mb-6">
                <canvas id="categoryChart"></canvas>
            </div>
            
            <div class="space-y-3">
                @php $colors = ['#6366f1', '#3b82f6', '#10b981', '#f59e0b', '#ec4899']; @endphp
                @foreach($categoryLabels as $index => $label)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full mr-3" style="background-color: {{ $colors[$index % count($colors)] }}"></div>
                        <span class="text-sm font-medium text-slate-300">{{ $label }}</span>
                    </div>
                    <span class="text-sm text-slate-400">
                        @php 
                            $total = array_sum($categoryData); 
                            $percent = $total > 0 ? round(($categoryData[$index] / $total) * 100) : 0;
                        @endphp
                        {{ $percent }}% ({{ $categoryData[$index] }})
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Bottom Data Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Borrowed Books -->
        <div class="dark-card rounded-3xl p-6 flex flex-col">
            <h3 class="text-lg font-bold text-white mb-6">Top Borrowed Books</h3>
            
            <div class="flex-1">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-slate-400 text-[10px] uppercase tracking-wider border-b border-slate-700/80">
                            <th class="pb-3 font-medium w-8">#</th>
                            <th class="pb-3 font-medium">Book Title</th>
                            <th class="pb-3 font-medium hidden sm:table-cell">Author</th>
                            <th class="pb-3 font-medium text-right">Times Borrowed</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-700/50">
                        @forelse($topBooks as $index => $book)
                        <tr class="group">
                            <td class="py-3 text-slate-500 font-medium">{{ $index + 1 }}</td>
                            <td class="py-3">
                                <div class="flex items-center">
                                    <div class="w-8 h-10 bg-slate-700 rounded shadow mr-3 flex items-center justify-center shrink-0 border border-slate-600">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    </div>
                                    <span class="font-bold text-slate-200">{{ $book->title }}</span>
                                </div>
                            </td>
                            <td class="py-3 text-slate-400 text-xs hidden sm:table-cell">{{ $book->author->name ?? 'Unknown' }}</td>
                            <td class="py-3 text-right">
                                <span class="bg-indigo-500/10 text-indigo-400 font-bold px-2 py-1 rounded text-xs">{{ $book->borrow_records_count }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-slate-500">No data available.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4 pt-4 border-t border-slate-700/50 text-center">
                <a href="{{ route('books.index') }}" class="text-xs font-bold text-indigo-400 hover:text-indigo-300 transition-colors uppercase tracking-wider">View All Books</a>
            </div>
        </div>

        <!-- Member Activity Summary -->
        <div class="dark-card rounded-3xl p-6 flex flex-col">
            <h3 class="text-lg font-bold text-white mb-6">Member Activity Summary</h3>
            
            <div class="space-y-4 flex-1">
                <!-- Row 1 -->
                <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-800/30 border border-slate-700/50 hover:bg-slate-800/50 transition-colors">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-blue-500/10 text-blue-400 flex items-center justify-center mr-4 border border-blue-500/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-200 text-sm">New Members Registered</h4>
                            <p class="text-[11px] text-slate-500">Total new members this period</p>
                        </div>
                    </div>
                    <span class="text-2xl font-bold text-white">{{ $activitySummary['new_members'] }}</span>
                </div>

                <!-- Row 2 -->
                <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-800/30 border border-slate-700/50 hover:bg-slate-800/50 transition-colors">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-indigo-500/10 text-indigo-400 flex items-center justify-center mr-4 border border-indigo-500/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-200 text-sm">Books Borrowed</h4>
                            <p class="text-[11px] text-slate-500">Total books borrowed this period</p>
                        </div>
                    </div>
                    <span class="text-2xl font-bold text-white">{{ $activitySummary['books_borrowed'] }}</span>
                </div>

                <!-- Row 3 -->
                <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-800/30 border border-slate-700/50 hover:bg-slate-800/50 transition-colors">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-emerald-500/10 text-emerald-400 flex items-center justify-center mr-4 border border-emerald-500/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-200 text-sm">Books Returned</h4>
                            <p class="text-[11px] text-slate-500">Total books returned this period</p>
                        </div>
                    </div>
                    <span class="text-2xl font-bold text-white">{{ $activitySummary['books_returned'] }}</span>
                </div>

                <!-- Row 4 -->
                <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-800/30 border border-slate-700/50 hover:bg-slate-800/50 transition-colors">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-red-500/10 text-red-400 flex items-center justify-center mr-4 border border-red-500/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-200 text-sm">Overdue Books</h4>
                            <p class="text-[11px] text-slate-500">Total overdue books this period</p>
                        </div>
                    </div>
                    <span class="text-2xl font-bold text-white">{{ $activitySummary['overdue_books'] }}</span>
                </div>
            </div>
            
            <div class="mt-4 pt-4 border-t border-slate-700/50 text-center">
                <a href="{{ route('borrowings.index') }}" class="text-xs font-bold text-indigo-400 hover:text-indigo-300 transition-colors uppercase tracking-wider">View Detailed Report</a>
            </div>
        </div>
    </div>

    <!-- Add Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Setup common chart styling for dark theme
            Chart.defaults.color = '#94a3b8';
            Chart.defaults.font.family = 'Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';

            // 1. Line Chart: Books Borrowed Over Time
            const ctxBorrowings = document.getElementById('borrowingsChart').getContext('2d');
            
            // Create gradient
            let gradient = ctxBorrowings.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(99, 102, 241, 0.5)'); // Indigo
            gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

            new Chart(ctxBorrowings, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartDates) !!},
                    datasets: [{
                        label: 'Books Borrowed',
                        data: {!! json_encode($chartBorrows) !!},
                        borderColor: '#6366f1',
                        backgroundColor: gradient,
                        borderWidth: 2,
                        pointBackgroundColor: '#6366f1',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#6366f1',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleColor: '#f8fafc',
                            bodyColor: '#cbd5e1',
                            borderColor: '#334155',
                            borderWidth: 1,
                            padding: 10,
                            displayColors: false,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(51, 65, 85, 0.5)',
                                drawBorder: false,
                            },
                            ticks: {
                                padding: 10,
                                stepSize: 20
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false,
                            },
                            ticks: {
                                padding: 10,
                                maxTicksLimit: 7
                            }
                        }
                    }
                }
            });

            // 2. Donut Chart: Books by Category
            const ctxCategory = document.getElementById('categoryChart').getContext('2d');
            new Chart(ctxCategory, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($categoryLabels) !!},
                    datasets: [{
                        data: {!! json_encode($categoryData) !!},
                        backgroundColor: [
                            '#6366f1', // Indigo
                            '#3b82f6', // Blue
                            '#10b981', // Emerald
                            '#f59e0b', // Amber
                            '#ec4899', // Pink
                            '#8b5cf6', // Purple
                            '#14b8a6', // Teal
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleColor: '#f8fafc',
                            bodyColor: '#cbd5e1',
                            borderColor: '#334155',
                            borderWidth: 1,
                            padding: 10,
                        }
                    }
                }
            });
        });
    </script>
@endsection

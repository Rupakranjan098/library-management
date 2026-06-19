@extends('layouts.admin')

@section('title', 'Dashboard - Library Management')

@section('content')
    <div>
        <h1 class="text-2xl font-bold text-white tracking-tight">Dashboard</h1>
        <p class="text-slate-400 mt-1 text-sm">Overview of your library system</p>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6 mt-6 mb-8">
        <!-- Total Books -->
        <div class="dark-card rounded-2xl p-5 flex items-center transition-transform hover:-translate-y-1 duration-300">
            <div class="w-14 h-14 rounded-full bg-indigo-600 flex items-center justify-center text-white glow-purple shrink-0 mr-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <div>
                <p class="text-slate-400 text-xs font-medium mb-1 uppercase tracking-wider">Total Books</p>
                <h3 class="text-2xl font-bold text-white tracking-tight">{{ number_format($totalBooks) }}</h3>
            </div>
        </div>

        <!-- Total Members -->
        <div class="dark-card rounded-2xl p-5 flex items-center transition-transform hover:-translate-y-1 duration-300">
            <div class="w-14 h-14 rounded-full bg-blue-600 flex items-center justify-center text-white glow-blue shrink-0 mr-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-slate-400 text-xs font-medium mb-1 uppercase tracking-wider">Total Members</p>
                <h3 class="text-2xl font-bold text-white tracking-tight">{{ number_format($totalMembers) }}</h3>
            </div>
        </div>

        <!-- Books Borrowed -->
        <div class="dark-card rounded-2xl p-5 flex items-center transition-transform hover:-translate-y-1 duration-300">
            <div class="w-14 h-14 rounded-full bg-green-500 flex items-center justify-center text-white glow-green shrink-0 mr-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
            </div>
            <div>
                <p class="text-slate-400 text-xs font-medium mb-1 uppercase tracking-wider">Books Borrowed</p>
                <h3 class="text-2xl font-bold text-white tracking-tight">{{ number_format($borrowedBooks) }}</h3>
            </div>
        </div>

        <!-- Overdue Books -->
        <div class="dark-card rounded-2xl p-5 flex items-center transition-transform hover:-translate-y-1 duration-300">
            <div class="w-14 h-14 rounded-full bg-orange-500 flex items-center justify-center text-white glow-orange shrink-0 mr-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-slate-400 text-xs font-medium mb-1 uppercase tracking-wider">Overdue Books</p>
                <h3 class="text-2xl font-bold text-white tracking-tight">{{ number_format($overdueBooks) }}</h3>
            </div>
        </div>

        <!-- Total Fines -->
        <div class="dark-card rounded-2xl p-5 flex items-center transition-transform hover:-translate-y-1 duration-300">
            <div class="w-14 h-14 rounded-full bg-rose-600 flex items-center justify-center text-white glow-red shrink-0 mr-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-slate-400 text-xs font-medium mb-1 uppercase tracking-wider">Total Fines</p>
                <h3 class="text-2xl font-bold text-white tracking-tight">₹{{ number_format($totalFines) }}</h3>
            </div>
        </div>
    </div>

    <!-- Middle Section: Chart & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- Chart Widget -->
        <div class="lg:col-span-2 dark-card rounded-2xl p-6 flex flex-col">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-bold text-white">Borrowings Overview</h3>
                <select class="bg-slate-800 border border-slate-700 text-slate-300 text-xs rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block px-3 py-1.5 outline-none">
                    <option>Last 15 Days</option>
                </select>
            </div>
            <div class="flex-1 relative w-full h-64">
                <canvas id="borrowingsChart"></canvas>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="dark-card rounded-2xl p-6">
            <h3 class="text-base font-bold text-white mb-6">Quick Actions</h3>
            <div class="space-y-3">
                <a href="#" class="w-full bg-[#151b2b] hover:bg-slate-800 border border-slate-700 hover:border-indigo-500/50 rounded-xl p-3 flex items-center justify-between transition-colors group">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded bg-indigo-600/20 flex items-center justify-center text-indigo-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </div>
                        <span class="ml-3 font-medium text-sm text-slate-300 group-hover:text-white transition-colors">Add New Book</span>
                    </div>
                    <svg class="w-4 h-4 text-slate-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>

                <a href="#" class="w-full bg-[#151b2b] hover:bg-slate-800 border border-slate-700 hover:border-blue-500/50 rounded-xl p-3 flex items-center justify-between transition-colors group">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded bg-blue-600/20 flex items-center justify-center text-blue-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        </div>
                        <span class="ml-3 font-medium text-sm text-slate-300 group-hover:text-white transition-colors">Add New Member</span>
                    </div>
                    <svg class="w-4 h-4 text-slate-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Bottom Section: Tables & Lists -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Recent Borrowings Table -->
        <div class="lg:col-span-2 dark-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-bold text-white">Recent Borrowings</h3>
                <a href="#" class="text-indigo-400 hover:text-indigo-300 text-xs font-semibold transition-colors">View all</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-slate-400 text-xs uppercase tracking-wider border-b border-slate-700">
                            <th class="pb-3 font-medium">Member</th>
                            <th class="pb-3 font-medium">Book</th>
                            <th class="pb-3 font-medium">Issue Date</th>
                            <th class="pb-3 font-medium text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-700/50">
                        @forelse($recentBorrowings as $record)
                        <tr class="hover:bg-slate-800/50 transition-colors">
                            <td class="py-3.5 flex items-center">
                                <div class="w-7 h-7 rounded-full bg-indigo-600 flex items-center justify-center font-bold text-[10px] text-white mr-3">
                                    {{ substr($record->member->name, 0, 1) }}
                                </div>
                                <span class="font-medium text-slate-300">{{ $record->member->name }}</span>
                            </td>
                            <td class="py-3.5 text-slate-400 text-xs">{{ $record->book->title }}</td>
                            <td class="py-3.5 text-slate-400 text-xs">{{ \Carbon\Carbon::parse($record->borrow_date)->format('M d, Y') }}</td>
                            <td class="py-3.5 text-right">
                                @if($record->status === 'borrowed')
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20">Issued</span>
                                @elseif($record->status === 'overdue')
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-semibold bg-red-500/10 text-red-400 border border-red-500/20">Overdue</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-semibold bg-green-500/10 text-green-400 border border-green-500/20">Returned</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-slate-500 text-sm">No recent borrowings found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="dark-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-bold text-white">Recent Activities</h3>
            </div>

            <div class="space-y-5 relative before:absolute before:inset-0 before:ml-4 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-700 before:to-transparent">
                @forelse($activities as $activity)
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full border border-slate-700 bg-slate-800 text-{{ $activity->color }}-400 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">{!! $activity->icon !!}</svg>
                    </div>
                    <div class="w-[calc(100%-3rem)] md:w-[calc(50%-1.5rem)] ml-4 md:ml-0 {{ $loop->odd ? 'md:text-right' : 'md:text-left' }}">
                        <div class="flex items-center justify-between {{ $loop->odd ? 'md:justify-end' : 'md:justify-start' }} mb-0.5">
                            <h4 class="font-semibold text-slate-200 text-xs">{{ $activity->title }}</h4>
                        </div>
                        <p class="text-[11px] text-slate-400">{{ $activity->description }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center text-slate-500 text-sm py-4">No recent activities.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if(!document.getElementById('borrowingsChart')) return;
            const ctx = document.getElementById('borrowingsChart').getContext('2d');
            let gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(139, 92, 246, 0.2)'); 
            gradient.addColorStop(1, 'rgba(139, 92, 246, 0.0)');

            const dataPoints = @json($chartData);
            const labels = @json($chartLabels);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Borrowings',
                        data: dataPoints,
                        borderColor: '#8b5cf6', 
                        borderWidth: 2,
                        backgroundColor: gradient,
                        fill: true,
                        pointBackgroundColor: '#1e293b',
                        pointBorderColor: '#8b5cf6',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            borderColor: '#334155',
                            borderWidth: 1,
                            padding: 10,
                            titleFont: { size: 12, family: 'Inter' },
                            bodyFont: { size: 12, family: 'Inter' },
                            displayColors: false,
                            cornerRadius: 6,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#334155',
                                drawBorder: false,
                                borderDash: [5, 5]
                            },
                            ticks: {
                                color: '#64748b',
                                font: { family: 'Inter', size: 10 },
                                stepSize: 20
                            }
                        },
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: {
                                color: '#64748b',
                                font: { family: 'Inter', size: 10 },
                                maxTicksLimit: 7
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                }
            });
        });
    </script>
@endpush

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Library - Discover Your Next Great Read</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .bg-brand { background-color: #6366f1; }
        .bg-brand-dark { background-color: #4f46e5; }
        .text-brand { color: #6366f1; }
        .border-brand { border-color: #6366f1; }
        .glass-hero { background: radial-gradient(circle at 80% 20%, #eff6ff 0%, #ffffff 100%); }
    </style>
</head>
<body class="antialiased text-slate-800">

    <!-- Top Navbar -->
    <nav class="bg-gradient-to-r from-indigo-900 via-indigo-950 to-purple-950 text-white px-8 py-4 flex items-center justify-between shadow-lg relative z-20">
        <div class="flex items-center space-x-3">
            <div class="bg-white/10 p-2.5 rounded-xl backdrop-blur-md border border-white/10">
                <svg class="w-7 h-7 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <div>
                <span class="text-lg font-extrabold tracking-wide block leading-none">{{ $site['library_name'] }}</span>
                <span class="text-[10px] text-indigo-200 tracking-wider font-semibold uppercase">{{ $site['library_tagline'] }}</span>
            </div>
        </div>

        <!-- Center links -->
        <div class="hidden lg:flex items-center space-x-2">
            <a href="{{ route('catalog.index') }}" class="text-white hover:text-indigo-200 font-semibold py-2 px-4 bg-white/10 rounded-full transition-all text-sm">Home</a>
            <a href="#categories" class="text-white/75 hover:text-white font-semibold py-2 px-4 transition-all text-sm">Categories</a>
            <a href="#recommended" class="text-white/75 hover:text-white font-semibold py-2 px-4 transition-all text-sm">New Arrivals</a>
            <a href="#about" class="text-white/75 hover:text-white font-semibold py-2 px-4 transition-all text-sm">About Us</a>
            <a href="#contact" class="text-white/75 hover:text-white font-semibold py-2 px-4 transition-all text-sm">Contact</a>
        </div>

        <!-- Right Side Icons & Login -->
        <div class="flex items-center space-x-4">
            <!-- Notification bell -->
            <button class="relative p-2 text-white/85 hover:text-white hover:bg-white/10 rounded-full transition-colors">
                <span class="absolute top-1 right-1 w-4 h-4 bg-rose-500 rounded-full flex items-center justify-center text-[9px] font-bold text-white leading-none border border-indigo-950">3</span>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </button>
            
            <!-- Login Button -->
            <a href="{{ route('login') }}" class="flex items-center px-5 py-2 bg-white text-indigo-900 hover:bg-indigo-50 border border-white/20 rounded-full text-sm font-bold shadow-md transition-all">
                <svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Login
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="glass-hero relative overflow-hidden pt-12 pb-16 border-b border-slate-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-8 relative z-10 flex flex-col md:flex-row items-center">
            
            <!-- Hero Text & Search -->
            <div class="w-full md:w-1/2 pr-8 mt-4 md:mt-0">
                <!-- Welcome Capsule -->
                <div class="inline-flex items-center space-x-2 bg-indigo-50 border border-indigo-100 rounded-full px-4 py-1.5 text-[11px] font-bold text-indigo-600 mb-6 shadow-sm">
                    <svg class="w-3.5 h-3.5 text-indigo-500 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                    <span>Welcome to {{ $site['library_name'] }}</span>
                </div>

                <h1 class="text-5xl md:text-6xl font-extrabold text-slate-900 leading-none tracking-tight mb-4">
                    {{ $site['hero_title'] }}
                </h1>
                <p class="text-slate-600 mb-8 font-medium leading-relaxed max-w-lg">
                    {{ $site['hero_subtitle'] }}
                </p>
                
                <!-- Search Bar Form -->
                <form action="{{ route('catalog.index') }}" method="GET" id="searchForm" class="flex items-center bg-white p-2.5 rounded-2xl shadow-[0_12px_40px_rgb(99,102,241,0.06)] border border-slate-100 max-w-2xl">
                    <div class="flex-1 flex items-center pl-4 border-r border-slate-200">
                        <svg class="w-5 h-5 text-slate-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input type="text" id="searchInput" name="search" value="{{ request('search') }}" placeholder="Search by title, author or category..." class="w-full py-3 pr-4 outline-none text-slate-700 placeholder-slate-400 bg-transparent text-sm">
                    </div>
                    <div class="px-4 hidden sm:block">
                        <select name="category_id" class="outline-none text-sm text-slate-600 font-semibold bg-transparent cursor-pointer">
                            <option value="all">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="bg-brand hover:bg-indigo-600 text-white px-7 py-3.5 rounded-xl font-bold flex items-center transition-all shadow-md shadow-indigo-500/30 text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Search
                    </button>
                </form>

                <!-- Popular searches -->
                <div class="flex items-center flex-wrap gap-2 text-xs mt-5 text-slate-500">
                    <span class="font-bold text-slate-400 mr-1">Popular Searches:</span>
                    <button type="button" onclick="quickSearch('Foundation')" class="px-3.5 py-1.5 bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 rounded-full transition-colors font-medium text-slate-600 border border-slate-200/50">Foundation</button>
                    <button type="button" onclick="quickSearch('The Hobbit')" class="px-3.5 py-1.5 bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 rounded-full transition-colors font-medium text-slate-600 border border-slate-200/50">The Hobbit</button>
                    <button type="button" onclick="quickSearch('Cosmos')" class="px-3.5 py-1.5 bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 rounded-full transition-colors font-medium text-slate-600 border border-slate-200/50">Cosmos</button>
                </div>
            </div>

            <!-- Hero 3D Illustration -->
            <div class="w-full md:w-1/2 flex justify-center md:justify-end mt-12 md:mt-0 relative">
                <div class="absolute inset-0 bg-gradient-to-tr from-indigo-100 to-purple-50 rounded-full blur-3xl opacity-50 transform scale-90"></div>
                <img src="{{ asset('images/hero_girl.png') }}" alt="Girl reading a book" class="relative z-10 w-full max-w-lg object-contain drop-shadow-2xl">
            </div>

        </div>
    </header>

    <!-- Stats & Become Member Section -->
    <section class="max-w-7xl mx-auto px-8 py-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
            
            <!-- Stat 1: Total Book Titles -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] flex items-center space-x-4">
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center shrink-0 border border-indigo-100/50">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <div>
                    <span id="stat-total-books" class="text-2xl font-extrabold text-slate-900 block leading-tight" data-target="{{ $stats['total_books'] }}">0</span>
                    <span class="text-xs text-slate-500 font-semibold uppercase tracking-wider block">Book Titles</span>
                    <span class="text-[10px] text-slate-400 mt-0.5 block font-medium">{{ $stats['total_copies'] }} total copies</span>
                </div>
            </div>

            <!-- Stat 2: Members -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center shrink-0 border border-blue-100/50">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <span id="stat-members" class="text-2xl font-extrabold text-slate-900 block leading-tight" data-target="{{ $stats['total_members'] }}">0</span>
                    <span class="text-xs text-slate-500 font-semibold uppercase tracking-wider block">Members</span>
                    <span class="text-[10px] text-slate-400 mt-0.5 block font-medium">Active library members</span>
                </div>
            </div>

            <!-- Stat 3: Books Borrowed -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] flex items-center space-x-4">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shrink-0 border border-emerald-100/50">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
                <div>
                    <span id="stat-borrowed" class="text-2xl font-extrabold text-slate-900 block leading-tight" data-target="{{ $stats['books_borrowed'] }}">0</span>
                    <span class="text-xs text-slate-500 font-semibold uppercase tracking-wider block">Books Borrowed</span>
                    <span class="text-[10px] text-slate-400 mt-0.5 block font-medium">Currently checked out</span>
                </div>
            </div>

            <!-- Stat 4: Authors -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] flex items-center space-x-4">
                <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center shrink-0 border border-orange-100/50">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <div>
                    <span id="stat-authors" class="text-2xl font-extrabold text-slate-900 block leading-tight" data-target="{{ $stats['total_authors'] }}">0</span>
                    <span class="text-xs text-slate-500 font-semibold uppercase tracking-wider block">Authors</span>
                    <span class="text-[10px] text-slate-400 mt-0.5 block font-medium">{{ $stats['total_categories'] }} categories</span>
                </div>
            </div>

            <!-- Become Member Banner Card -->
            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 border border-indigo-100 rounded-3xl p-5 flex items-center justify-between relative overflow-hidden shadow-sm">
                <div class="flex-1 z-10">
                    <span class="text-[10px] text-indigo-600 font-bold uppercase tracking-wider block mb-1">Become a Member</span>
                    <p class="text-xs text-slate-600 font-medium mb-3 max-w-[120px] leading-snug">Join our community and get unlimited access!</p>
                    <a href="{{ route('register') }}" class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-[10px] font-bold transition-all shadow-md shadow-indigo-600/20 inline-flex items-center">
                        Join Now
                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
                <!-- Stack illustration on right -->
                <img src="{{ asset('images/books_stack.png') }}" class="w-14 object-contain drop-shadow-md z-10 shrink-0 transform translate-x-1.5 translate-y-1">
                <div class="absolute -bottom-6 -right-6 w-16 h-16 bg-indigo-500/5 rounded-full blur-xl"></div>
            </div>

        </div>
    </section>

    <!-- Popular Categories -->
    <section id="categories" class="max-w-7xl mx-auto px-8 py-10">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-extrabold text-slate-900">Popular Categories</h2>
            <a href="{{ route('catalog.index') }}" class="text-brand hover:text-indigo-700 font-bold text-sm flex items-center group">
                View All Categories
                <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
            @foreach($categories as $category)
                @php
                    $catName = $category->name;
                    $config = [
                        'Science Fiction' => ['icon' => 'beaker', 'color' => 'bg-indigo-50 text-indigo-600 hover:bg-indigo-100 border-indigo-100/50'],
                        'Fantasy' => ['icon' => 'sparkles', 'color' => 'bg-purple-50 text-purple-600 hover:bg-purple-100 border-purple-100/50'],
                        'Non-Fiction' => ['icon' => 'lightbulb', 'color' => 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border-emerald-100/50'],
                        'General' => ['icon' => 'book', 'color' => 'bg-slate-50 text-slate-600 hover:bg-slate-100 border-slate-200/50']
                    ];
                    $c = $config[$catName] ?? $config['General'];
                @endphp
                
                <a href="{{ route('catalog.index', ['category_id' => $category->id]) }}" class="bg-white rounded-3xl p-5 border border-slate-100 shadow-[0_4px_20px_rgb(0,0,0,0.02)] flex flex-col items-center text-center transition-all hover:-translate-y-1 hover:shadow-md hover:border-slate-200 group">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4 transition-all {{ $c['color'] }} border shadow-sm">
                        @if($c['icon'] === 'beaker')
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        @elseif($c['icon'] === 'sparkles')
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                        @elseif($c['icon'] === 'lightbulb')
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                        @else
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        @endif
                    </div>
                    <span class="text-sm font-bold text-slate-800 group-hover:text-indigo-600 transition-colors leading-tight mb-1">{{ $catName }}</span>
                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide">{{ $category->books_count ?? 0 }} Books</span>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Catalog Catalog / Recommended section -->
    <main id="recommended" class="max-w-7xl mx-auto px-8 py-10">
        
        <!-- Section Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-2">
                <div class="w-10 h-10 bg-indigo-50 border border-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.906a1 1 0 00.95-.69l1.519-4.674z"></path></svg>
                </div>
                <h2 class="text-2xl font-extrabold text-slate-900">Recommended for You</h2>
            </div>
            
            <!-- Dynamic Navigation Arrows -->
            <div class="flex items-center space-x-2">
                <button class="w-9 h-9 border border-slate-200 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button class="w-9 h-9 border border-slate-200 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
        </div>

        @if($books->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-100 shadow-sm max-w-xl mx-auto mb-16">
                <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">No Books Found</h3>
                <p class="text-slate-500 text-sm mb-6">No matching books found in our catalog for your search query.</p>
                <a href="{{ route('catalog.index') }}" class="px-6 py-3 bg-brand hover:bg-indigo-600 text-white rounded-xl font-semibold inline-flex items-center transition-colors shadow-md shadow-indigo-500/20">
                    Reset Catalog Grid
                </a>
            </div>
        @else
            <!-- Dynamic Grid of Book Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
                @foreach($books as $book)
                    @php
                        $categoryName = $book->category->name ?? 'General';
                        
                        // cover design categories
                        $coverGradients = [
                            'Science Fiction' => 'from-indigo-900 to-slate-900',
                            'Fantasy' => 'from-purple-900 to-indigo-950',
                            'Non-Fiction' => 'from-emerald-900 to-slate-950',
                            'General' => 'from-slate-800 to-slate-900'
                        ];
                        $grad = $coverGradients[$categoryName] ?? $coverGradients['General'];

                        // deterministic dynamic rating & counts
                        $rating = number_format(4.3 + (($book->id * 3) % 7) * 0.1, 1);
                        $ratingCount = number_format(1500 + ($book->id * 2381) % 15000);
                    @endphp

                    <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-[0_4px_25px_rgb(0,0,0,0.03)] hover:-translate-y-1.5 hover:shadow-[0_12px_30px_rgb(0,0,0,0.06)] transition-all flex flex-col group relative">
                        
                        <!-- Dynamic bookmark button -->
                        <button onclick="toggleBookmark('{{ $book->id }}')" class="absolute top-4 right-4 z-10 p-2 rounded-full bg-white/70 hover:bg-white text-slate-400 hover:text-indigo-600 transition-all border border-slate-100 shadow-sm">
                            <svg id="bookmark-icon-{{ $book->id }}" class="w-4 h-4 fill-none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                        </button>

                        <!-- Dynamic Cover Art Block -->
                        <div class="w-full aspect-[3/4] rounded-2xl bg-gradient-to-br {{ $grad }} flex flex-col items-center justify-center p-5 text-center border border-slate-800/40 relative overflow-hidden mb-4 shadow-md">
                            <div class="absolute -top-12 -left-12 w-28 h-28 bg-white/5 rounded-full blur-2xl"></div>
                            <span class="text-[9px] font-bold text-white/50 uppercase tracking-widest leading-none mb-2">{{ $book->author->name ?? 'Author' }}</span>
                            <h4 class="text-white font-extrabold text-base leading-tight relative z-10 px-2 line-clamp-3">{{ $book->title }}</h4>
                            <div class="absolute bottom-3 left-3 right-3 flex justify-between items-center z-10">
                                <span class="text-[8px] bg-white/10 px-2 py-0.5 rounded text-white/70 border border-white/10 font-bold tracking-wide">ISBN: {{ $book->isbn }}</span>
                            </div>
                        </div>

                        <!-- Info details -->
                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="font-bold text-slate-900 leading-snug text-base line-clamp-1 mb-1">{{ $book->title }}</h3>
                                <p class="text-xs text-slate-500 font-medium mb-3">By <span class="text-slate-700 font-semibold">{{ $book->author->name ?? 'Unknown' }}</span></p>
                            </div>

                            <!-- Rating & Availability Details -->
                            <div>
                                <div class="flex items-center space-x-1.5 text-xs font-semibold text-slate-500 mb-4">
                                    <svg class="w-4 h-4 text-amber-400 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                    <span class="text-slate-800 font-bold">{{ $rating }}</span>
                                    <span class="text-slate-400">({{ $ratingCount }})</span>
                                </div>

                                <div class="flex items-center justify-between border-t border-slate-100 pt-4 mt-2">
                                    <!-- Stock check -->
                                    <div class="flex items-center space-x-1.5 text-xs font-bold">
                                        @if($book->available_copies > 0)
                                            <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block animate-pulse"></span>
                                            <span class="text-emerald-600 font-bold" id="available-count-{{ $book->id }}">
                                                {{ $book->available_copies }} / {{ $book->total_copies }} Available
                                            </span>
                                        @else
                                            <span class="w-2 h-2 rounded-full bg-rose-500 inline-block"></span>
                                            <span class="text-rose-600 font-bold" id="available-count-{{ $book->id }}">
                                                Out of Stock
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Action Button -->
                                    @if($book->available_copies > 0)
                                        <button id="reserve-btn-{{ $book->id }}" onclick="openReserveModal('{{ $book->id }}', '{{ addslashes($book->title) }}', '{{ addslashes($book->author->name ?? 'Unknown') }}', '{{ addslashes($categoryName) }}')" class="px-4 py-2 bg-indigo-50 hover:bg-indigo-600 text-indigo-600 hover:text-white rounded-xl text-xs font-bold transition-all border border-indigo-100 hover:border-indigo-600">
                                            Reserve
                                        </button>
                                    @else
                                        <button id="reserve-btn-{{ $book->id }}" disabled class="px-4 py-2 bg-slate-100 text-slate-400 rounded-xl text-xs font-bold cursor-not-allowed border border-slate-200/50">
                                            Out
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

        <!-- Bottom Banner / Features Row -->
        <div class="bg-indigo-50/50 rounded-3xl p-8 flex flex-col md:flex-row items-center justify-between border border-indigo-100/50 shadow-sm gap-8 relative overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 z-10 w-full md:w-3/4">
                <!-- Feature 1 -->
                <div class="flex items-start">
                    <div class="w-10 h-10 rounded-xl bg-white text-indigo-600 shadow-sm flex items-center justify-center shrink-0 mr-4 border border-indigo-100/50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm mb-1 leading-none">Wide Collection</h4>
                        <p class="text-[11px] text-slate-500 leading-normal">Thousands of books across multiple genres.</p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="flex items-start">
                    <div class="w-10 h-10 rounded-xl bg-white text-indigo-600 shadow-sm flex items-center justify-center shrink-0 mr-4 border border-indigo-100/50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm mb-1 leading-none">Easy Borrowing</h4>
                        <p class="text-[11px] text-slate-500 leading-normal">Simple process to borrow and return books.</p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="flex items-start">
                    <div class="w-10 h-10 rounded-xl bg-white text-indigo-600 shadow-sm flex items-center justify-center shrink-0 mr-4 border border-indigo-100/50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm mb-1 leading-none">Community Events</h4>
                        <p class="text-[11px] text-slate-500 leading-normal">Join workshops, reading clubs and sessions.</p>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div class="flex items-start">
                    <div class="w-10 h-10 rounded-xl bg-white text-indigo-600 shadow-sm flex items-center justify-center shrink-0 mr-4 border border-indigo-100/50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm mb-1 leading-none">Digital Resources</h4>
                        <p class="text-[11px] text-slate-500 leading-normal">Access e-books, audiobooks and databases.</p>
                    </div>
                </div>
            </div>

            <!-- Stack with headphones illustration on right -->
            <div class="w-full md:w-1/4 flex justify-end shrink-0 z-10 relative">
                <!-- Stack image with subtle absolute lighting -->
                <div class="absolute inset-0 bg-gradient-to-tr from-indigo-200 to-purple-100/20 rounded-full blur-2xl opacity-40"></div>
                <img src="{{ asset('images/books_stack.png') }}" alt="Stack of books" class="w-32 object-contain drop-shadow-xl relative z-10 mr-4">
            </div>
        </div>

    </main>

    <!-- ══════════════════════════════════════════════
         NEW ARRIVALS SECTION (backend-driven)
    ══════════════════════════════════════════════ -->
    <section id="new-arrivals" class="bg-gradient-to-b from-slate-50 to-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-8 py-14">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <span class="inline-flex items-center gap-2 text-[11px] font-bold text-indigo-600 uppercase tracking-widest bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100 mb-3">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Just Added
                    </span>
                    <h2 class="text-3xl font-extrabold text-slate-900 leading-tight">New Arrivals</h2>
                    <p class="text-slate-500 text-sm mt-1">The latest books added to our collection</p>
                </div>
                <a href="{{ route('catalog.index') }}" class="hidden md:flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors group">
                    Browse All Books
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @if($newArrivals->isEmpty())
                <div class="text-center py-16 text-slate-400 font-medium">No books in the library yet.</div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($newArrivals as $i => $book)
                        @php
                            $catName = $book->category->name ?? 'General';
                            $gradients = ['from-indigo-900 to-slate-900','from-purple-900 to-indigo-950','from-emerald-900 to-slate-950','from-slate-800 to-slate-900'];
                            $grad = $gradients[$i % 4];
                            $badgeColors = ['bg-indigo-100 text-indigo-700','bg-purple-100 text-purple-700','bg-emerald-100 text-emerald-700','bg-amber-100 text-amber-700'];
                            $badge = $badgeColors[$i % 4];
                        @endphp
                        <div class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-[0_4px_20px_rgb(0,0,0,0.04)] hover:-translate-y-1 hover:shadow-[0_12px_30px_rgb(0,0,0,0.08)] transition-all group">
                            <!-- Book cover -->
                            <div class="relative h-44 bg-gradient-to-br {{ $grad }} flex flex-col items-center justify-center p-6 text-center overflow-hidden">
                                <div class="absolute top-0 right-0 w-24 h-24 bg-white/5 rounded-full -translate-y-8 translate-x-8 blur-xl"></div>
                                <span class="text-[9px] font-bold text-white/50 uppercase tracking-widest mb-2">{{ $book->author->name ?? 'Unknown' }}</span>
                                <h4 class="text-white font-extrabold text-sm leading-tight line-clamp-3 relative z-10">{{ $book->title }}</h4>
                                <!-- NEW badge -->
                                <span class="absolute top-3 left-3 bg-white/20 backdrop-blur-sm text-white text-[9px] font-bold px-2 py-0.5 rounded-full border border-white/20">NEW</span>
                            </div>
                            <!-- Info -->
                            <div class="p-5">
                                <span class="text-[10px] font-bold uppercase tracking-wider {{ $badge }} px-2.5 py-1 rounded-full">{{ $catName }}</span>
                                <h3 class="font-bold text-slate-900 text-sm mt-3 mb-1 line-clamp-1">{{ $book->title }}</h3>
                                <p class="text-xs text-slate-500 mb-4">By <span class="font-semibold text-slate-700">{{ $book->author->name ?? 'Unknown' }}</span></p>
                                <div class="flex items-center justify-between">
                                    @if($book->available_copies > 0)
                                        <span class="flex items-center gap-1 text-xs font-bold text-emerald-600">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                            {{ $book->available_copies }} Available
                                        </span>
                                        <button onclick="openReserveModal('{{ $book->id }}', '{{ addslashes($book->title) }}', '{{ addslashes($book->author->name ?? 'Unknown') }}', '{{ addslashes($catName) }}')" class="text-xs font-bold px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl transition-all shadow-md shadow-indigo-500/20">Reserve</button>
                                    @else
                                        <span class="flex items-center gap-1 text-xs font-bold text-rose-500"><span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span> Out of Stock</span>
                                        <button disabled class="text-xs font-bold px-4 py-1.5 bg-slate-100 text-slate-400 rounded-xl cursor-not-allowed">Out</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- ══════════════════════════════════════════════
         ABOUT SECTION
    ══════════════════════════════════════════════ -->
    <section id="about" class="bg-gradient-to-br from-indigo-950 via-indigo-900 to-purple-950 text-white">
        <div class="max-w-7xl mx-auto px-8 py-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-14 items-center">
                <!-- Left: Text -->
                <div>
                    <span class="inline-block text-[11px] font-bold uppercase tracking-widest text-indigo-300 bg-white/10 px-3 py-1.5 rounded-full border border-white/10 mb-5">About Us</span>
                    <h2 class="text-4xl font-extrabold leading-tight mb-5">A Library Built for <span class="text-indigo-300">Everyone</span></h2>
                    <p class="text-indigo-100/80 text-sm leading-relaxed mb-8 max-w-lg">
                        We believe knowledge should be accessible to all. Our library has been serving the community for decades, offering a wide selection of books, digital resources, and educational events for all ages.
                    </p>
                    <div class="grid grid-cols-2 gap-5 mb-8">
                        <div class="bg-white/10 backdrop-blur-sm border border-white/10 rounded-2xl p-5">
                            <span class="text-3xl font-extrabold text-white block">{{ $stats['total_books'] }}</span>
                            <span class="text-xs text-indigo-200 font-semibold uppercase tracking-wide">Book Titles</span>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm border border-white/10 rounded-2xl p-5">
                            <span class="text-3xl font-extrabold text-white block">{{ $stats['total_members'] }}</span>
                            <span class="text-xs text-indigo-200 font-semibold uppercase tracking-wide">Members</span>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm border border-white/10 rounded-2xl p-5">
                            <span class="text-3xl font-extrabold text-white block">{{ $stats['total_authors'] }}</span>
                            <span class="text-xs text-indigo-200 font-semibold uppercase tracking-wide">Authors</span>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm border border-white/10 rounded-2xl p-5">
                            <span class="text-3xl font-extrabold text-white block">{{ $stats['total_categories'] }}</span>
                            <span class="text-xs text-indigo-200 font-semibold uppercase tracking-wide">Categories</span>
                        </div>
                    </div>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-white text-indigo-900 font-bold px-6 py-3 rounded-2xl hover:bg-indigo-50 transition-all shadow-lg text-sm">
                        Become a Member
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                <!-- Right: Features list -->
                <div class="space-y-5">
                    @php
                    $features = [
                        ['icon'=>'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253','title'=>'Vast Book Collection','desc'=>'Thousands of titles across science, fiction, history, technology and more.'],
                        ['icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2','title'=>'Easy Borrowing System','desc'=>'Borrow books online or in person — returns automatically tracked.'],
                        ['icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z','title'=>'Community Events','desc'=>'Join reading clubs, author talks, and educational workshops.'],
                        ['icon'=>'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z','title'=>'Digital Resources','desc'=>'Access e-books, audiobooks, and research databases from anywhere.']
                    ];
                    @endphp
                    @foreach($features as $f)
                    <div class="flex items-start gap-4 bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-5 hover:bg-white/10 transition-all">
                        <div class="w-10 h-10 bg-indigo-500/20 border border-indigo-400/20 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $f['icon'] }}"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-sm mb-1">{{ $f['title'] }}</h4>
                            <p class="text-indigo-200/70 text-xs leading-relaxed">{{ $f['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- ══════════════════════════════════════════════
         CONTACT SECTION
    ══════════════════════════════════════════════ -->
    <section id="contact" class="bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-8 py-20">
            <!-- Section Header -->
            <div class="text-center mb-12">
                <span class="inline-block text-[11px] font-bold uppercase tracking-widest text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-full border border-indigo-100 mb-4">Get in Touch</span>
                <h2 class="text-3xl font-extrabold text-slate-900 mb-3">Contact Us</h2>
                <p class="text-slate-500 max-w-lg mx-auto text-sm leading-relaxed">Have a question or want to learn more? Drop us a message and we'll respond within 1–2 business days.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-10">
                <!-- Left: Info cards -->
                <div class="lg:col-span-2 space-y-5">
                    <!-- Visit -->
                    <div class="flex items-start gap-4 bg-slate-50 rounded-2xl p-5 border border-slate-100">
                        <div class="w-11 h-11 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-sm mb-1">Visit Us</h4>
                            <p class="text-slate-500 text-xs leading-relaxed">{{ $site['contact_address'] }}</p>
                        </div>
                    </div>
                    <!-- Phone -->
                    <div class="flex items-start gap-4 bg-slate-50 rounded-2xl p-5 border border-slate-100">
                        <div class="w-11 h-11 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-sm mb-1">Call Us</h4>
                            <p class="text-slate-500 text-xs">{{ $site['contact_phone'] }}</p>
                            <p class="text-slate-400 text-xs mt-0.5">{{ $site['contact_phone_hours'] }}</p>
                        </div>
                    </div>
                    <!-- Email -->
                    <div class="flex items-start gap-4 bg-slate-50 rounded-2xl p-5 border border-slate-100">
                        <div class="w-11 h-11 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-sm mb-1">Email Us</h4>
                            <p class="text-slate-500 text-xs"><a href="mailto:{{ $site['contact_email'] }}" class="hover:text-indigo-600 transition-colors">{{ $site['contact_email'] }}</a></p>
                            <p class="text-slate-400 text-xs mt-0.5">{{ $site['contact_email_note'] }}</p>
                        </div>
                    </div>
                    <!-- Hours -->
                    <div class="flex items-start gap-4 bg-slate-50 rounded-2xl p-5 border border-slate-100">
                        <div class="w-11 h-11 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-sm mb-1">Opening Hours</h4>
                            <p class="text-slate-500 text-xs leading-relaxed">{{ $site['hours_weekday'] }}<br>{{ $site['hours_saturday'] }}<br>{{ $site['hours_sunday'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Right: Contact Form -->
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-[0_8px_40px_rgb(0,0,0,0.04)] p-8">
                        <h3 class="text-lg font-extrabold text-slate-900 mb-6">Send a Message</h3>

                        <!-- Success/Error banners (hidden initially) -->
                        <div id="contactSuccess" class="hidden mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-semibold px-4 py-3.5 rounded-2xl">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span id="contactSuccessMsg"></span>
                        </div>
                        <div id="contactError" class="hidden mb-5 flex items-center gap-3 bg-red-50 border border-red-100 text-red-700 text-sm font-semibold px-4 py-3.5 rounded-2xl">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span id="contactErrorMsg">Something went wrong. Please try again.</span>
                        </div>

                        <form id="contactForm" onsubmit="submitContactForm(event)">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2" for="contact_name">Your Name</label>
                                    <input type="text" id="contact_name" name="name" required placeholder="e.g. Rahul Sharma" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 text-slate-800 text-sm bg-slate-50 transition-all" />
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2" for="contact_email">Email Address</label>
                                    <input type="email" id="contact_email" name="email" required placeholder="you@example.com" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 text-slate-800 text-sm bg-slate-50 transition-all" />
                                </div>
                            </div>
                            <div class="mb-5">
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2" for="contact_subject">Subject</label>
                                <input type="text" id="contact_subject" name="subject" required placeholder="What is your enquiry about?" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 text-slate-800 text-sm bg-slate-50 transition-all" />
                            </div>
                            <div class="mb-6">
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2" for="contact_message">Message</label>
                                <textarea id="contact_message" name="message" required rows="5" placeholder="Write your message here..." class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 text-slate-800 text-sm bg-slate-50 transition-all resize-none"></textarea>
                            </div>
                            <button type="submit" id="contactSubmitBtn" class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl transition-all shadow-lg shadow-indigo-500/20 text-sm flex items-center justify-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ══════════════════════════════════════════════
         FOOTER
    ══════════════════════════════════════════════ -->
    <footer class="bg-gradient-to-r from-indigo-950 via-indigo-900 to-purple-950 text-white">
        <div class="max-w-7xl mx-auto px-8 py-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-3">
                <div class="bg-white/10 p-2 rounded-xl border border-white/10">
                    <svg class="w-6 h-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div>
                    <span class="font-extrabold text-base block leading-none">{{ $site['library_name'] }}</span>
                    <span class="text-[10px] text-indigo-300 uppercase tracking-wider">{{ $site['library_tagline'] }}</span>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-6 text-sm text-indigo-200">
                <a href="{{ route('catalog.index') }}" class="hover:text-white transition-colors">Home</a>
                <a href="#categories" class="hover:text-white transition-colors">Categories</a>
                <a href="#new-arrivals" class="hover:text-white transition-colors">New Arrivals</a>
                <a href="#about" class="hover:text-white transition-colors">About</a>
                <a href="#contact" class="hover:text-white transition-colors">Contact</a>
                <a href="{{ route('login') }}" class="hover:text-white transition-colors">Admin Login</a>
            </div>
            <p class="text-indigo-300/60 text-xs text-center md:text-right">&copy; {{ date('Y') }} {{ $site['library_name'] }}. All rights reserved.</p>
        </div>
    </footer>


    <!-- Reservation Modal -->
    <div id="reserveModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-3xl p-8 max-w-md w-full border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.15)] transform scale-95 transition-transform duration-300">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-xl font-extrabold text-slate-900">Reserve Book</h3>
                    <p class="text-sm text-slate-500 mt-1">Register checkout reservation for this book.</p>
                </div>
                <button onclick="closeReserveModal()" class="text-slate-400 hover:text-slate-600 transition-colors p-1 rounded-lg hover:bg-slate-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Book Brief -->
            <div class="bg-slate-50 rounded-2xl p-4 mb-6 border border-slate-100 flex items-center">
                <div class="w-12 h-16 rounded-md bg-gradient-to-br from-indigo-600 to-purple-700 flex items-center justify-center text-white mr-4 shrink-0 shadow-sm">
                    <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <div>
                    <h4 id="modalBookTitle" class="font-bold text-slate-800 text-base leading-tight"></h4>
                    <p id="modalBookAuthor" class="text-xs text-slate-500 mt-1"></p>
                </div>
            </div>

            <!-- Reserve Form -->
            <form id="reserveForm" onsubmit="submitReservation(event)">
                <input type="hidden" id="modalBookId">
                
                <div class="mb-6">
                    <label for="member_id" class="block text-sm font-semibold text-slate-700 mb-2">Select Member Profile</label>
                    <select id="member_id" name="member_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-500 text-slate-700 font-medium bg-slate-50 cursor-pointer transition-colors text-sm">
                        <option value="">-- Choose Member --</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}">{{ $member->name }} ({{ $member->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div id="modalError" class="mb-4 text-xs font-semibold text-red-600 bg-red-50 border border-red-100 p-3 rounded-xl hidden animate-pulse"></div>

                <div class="flex space-x-3">
                    <button type="button" onclick="closeReserveModal()" class="flex-1 py-3 border border-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-50 transition-colors text-sm">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 py-3 bg-[#6366f1] hover:bg-indigo-600 text-white font-bold rounded-xl transition-colors shadow-md shadow-indigo-500/20 text-sm">
                        Confirm
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Global Toast Notifications -->
    <div id="toastContainer" class="fixed bottom-6 right-6 z-50 flex flex-col space-y-3 pointer-events-none"></div>

    <!-- Javascript Handlers -->
    <script>
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `flex items-center px-4 py-3.5 rounded-2xl border text-sm font-semibold shadow-lg backdrop-blur-md transform translate-y-4 opacity-0 transition-all duration-300 pointer-events-auto ${
                type === 'success' 
                    ? 'bg-emerald-50/90 border-emerald-100 text-emerald-800' 
                    : 'bg-red-50/90 border-red-100 text-red-800'
            }`;
            
            const icon = type === 'success' 
                ? '<svg class="w-4 h-4 mr-2.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>'
                : '<svg class="w-4 h-4 mr-2.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>';
                
            toast.innerHTML = icon + `<span>${message}</span>`;
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.remove('translate-y-4', 'opacity-0');
            }, 10);
            
            setTimeout(() => {
                toast.classList.add('translate-y-4', 'opacity-0');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 4000);
        }

        function toggleBookmark(bookId) {
            const icon = document.getElementById(`bookmark-icon-${bookId}`);
            if (icon.classList.contains('fill-none')) {
                icon.classList.remove('fill-none', 'text-slate-400');
                icon.classList.add('fill-current', 'text-indigo-600');
                showToast("Book bookmarked to reading list!", "success");
            } else {
                icon.classList.remove('fill-current', 'text-indigo-600');
                icon.classList.add('fill-none', 'text-slate-400');
                showToast("Bookmark removed.", "success");
            }
        }

        function quickSearch(term) {
            const searchInput = document.getElementById('searchInput');
            searchInput.value = term;
            document.getElementById('searchForm').submit();
        }

        function openReserveModal(bookId, bookTitle, bookAuthor, categoryName) {
            const modal = document.getElementById('reserveModal');
            const titleEl = document.getElementById('modalBookTitle');
            const authorEl = document.getElementById('modalBookAuthor');
            const idEl = document.getElementById('modalBookId');
            const errorEl = document.getElementById('modalError');
            const form = document.getElementById('reserveForm');
            
            idEl.value = bookId;
            titleEl.textContent = bookTitle;
            authorEl.textContent = `By ${bookAuthor}`;
            errorEl.classList.add('hidden');
            form.reset();
            
            modal.classList.remove('hidden');
            modal.offsetHeight; // force reflow
            modal.classList.add('opacity-100');
            modal.querySelector('.transform').classList.remove('scale-95');
            modal.querySelector('.transform').classList.add('scale-100');
        }

        function closeReserveModal() {
            const modal = document.getElementById('reserveModal');
            modal.classList.remove('opacity-100');
            modal.querySelector('.transform').classList.remove('scale-100');
            modal.querySelector('.transform').classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function submitReservation(event) {
            event.preventDefault();
            const bookId = document.getElementById('modalBookId').value;
            const memberId = document.getElementById('member_id').value;
            const errorEl = document.getElementById('modalError');
            
            if (!memberId) {
                errorEl.textContent = "Please select a member profile.";
                errorEl.classList.remove('hidden');
                return;
            }
            
            errorEl.classList.add('hidden');
            
            fetch(`/books/${bookId}/reserve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ member_id: memberId })
            })
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(res => {
                if (res.status === 200 && res.body.success) {
                    showToast(res.body.message, 'success');
                    closeReserveModal();
                    
                    // Live UI update
                    const availableCountEl = document.getElementById(`available-count-${bookId}`);
                    const reserveBtnEl = document.getElementById(`reserve-btn-${bookId}`);
                    const checkIconContainerEl = document.getElementById(`check-icon-${bookId}`);
                    
                    if (availableCountEl) {
                        // Keep availability count format matching total copies
                        availableCountEl.innerHTML = `${res.body.available_copies} / ${res.body.total_copies} Available`;
                    }
                    
                    if (res.body.available_copies <= 0) {
                        if (availableCountEl) {
                            availableCountEl.className = 'text-rose-600 font-bold';
                            availableCountEl.textContent = 'Out of Stock';
                        }
                        
                        if (reserveBtnEl) {
                            reserveBtnEl.disabled = true;
                            reserveBtnEl.className = 'px-4 py-2 bg-slate-100 text-slate-400 rounded-xl text-xs font-bold cursor-not-allowed border border-slate-200/50';
                            reserveBtnEl.textContent = 'Out';
                            reserveBtnEl.onclick = null;
                        }
                    }
                } else {
                    errorEl.textContent = res.body.message || "An error occurred during reservation.";
                    errorEl.classList.remove('hidden');
                }
            })
            .catch(err => {
                console.error(err);
                errorEl.textContent = "Failed to communicate with server. Please try again.";
                errorEl.classList.remove('hidden');
            });
        }

        // Running dynamic numbers animation
        function animateCountUp(el, target, duration = 1500) {
            let startTimestamp = null;
            const isEvents = el.id === 'stat-events';
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                const easedProgress = progress * (2 - progress);
                const currentVal = Math.floor(easedProgress * target);
                el.textContent = currentVal.toLocaleString() + (isEvents ? '' : '+');
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                } else {
                    el.textContent = target.toLocaleString() + (isEvents ? '' : '+');
                }
            };
            window.requestAnimationFrame(step);
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Animate all real stat counters
            ['stat-total-books','stat-members','stat-borrowed','stat-authors'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    const target = parseInt(el.getAttribute('data-target'), 10) || 0;
                    animateCountUp(el, target, 1500);
                }
            });
        });

        // ─── Contact Form Handler ───────────────────────────────────
        async function submitContactForm(e) {
            e.preventDefault();
            const btn = document.getElementById('contactSubmitBtn');
            const successBox = document.getElementById('contactSuccess');
            const successMsg = document.getElementById('contactSuccessMsg');
            const errorBox  = document.getElementById('contactError');
            const errorMsg  = document.getElementById('contactErrorMsg');

            btn.disabled = true;
            btn.innerHTML = `<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> Sending…`;
            successBox.classList.add('hidden');
            errorBox.classList.add('hidden');

            const payload = {
                name:    document.getElementById('contact_name').value.trim(),
                email:   document.getElementById('contact_email').value.trim(),
                subject: document.getElementById('contact_subject').value.trim(),
                message: document.getElementById('contact_message').value.trim(),
            };

            try {
                const res  = await fetch('{{ route("catalog.contact") }}', {
                    method:  'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body:    JSON.stringify(payload),
                });
                const data = await res.json();

                if (res.ok && data.success) {
                    successMsg.textContent = data.message;
                    successBox.classList.remove('hidden');
                    document.getElementById('contactForm').reset();
                } else {
                    const first = data.errors ? Object.values(data.errors)[0][0] : (data.message || 'Something went wrong.');
                    errorMsg.textContent = first;
                    errorBox.classList.remove('hidden');
                }
            } catch (err) {
                errorMsg.textContent = 'Network error. Please check your connection and try again.';
                errorBox.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg> Send Message`;
            }
        }
    </script>
</body>
</html>

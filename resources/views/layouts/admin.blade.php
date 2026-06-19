<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Library Management Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @stack('scripts')
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0f172a; color: #f8fafc; }
        .sidebar { background-color: #111827; border-right: 1px solid #1f2937; }
        .dark-card { background-color: #1e293b; border: 1px solid #334155; }
        
        /* Glowing Icons */
        .glow-purple { box-shadow: 0 0 20px rgba(139, 92, 246, 0.4); }
        .glow-blue { box-shadow: 0 0 20px rgba(59, 130, 246, 0.4); }
        .glow-green { box-shadow: 0 0 20px rgba(34, 197, 94, 0.4); }
        .glow-orange { box-shadow: 0 0 20px rgba(249, 115, 22, 0.4); }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }

        @stack('styles')
    </style>
</head>
<body class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="sidebar w-64 h-full flex flex-col transition-all duration-300 z-20">
        <!-- Logo -->
        <div class="h-20 flex items-center px-6 pt-6 mb-4">
            <div class="bg-indigo-600 text-white p-2 rounded-xl flex-shrink-0 glow-purple">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <div class="ml-3 font-bold text-white text-sm leading-tight">Laravel Library<br><span class="text-xs text-slate-400 font-normal">Management System</span></div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 space-y-1.5 mt-4 overflow-y-auto">
            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-[0_4px_15px_rgba(79,70,229,0.3)]' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }} rounded-xl transition-colors group">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? '' : 'group-hover:text-white transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="font-medium text-sm tracking-wide">Dashboard</span>
            </a>
            <a href="{{ route('authors.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('authors.*') ? 'bg-indigo-600 text-white shadow-[0_4px_15px_rgba(79,70,229,0.3)]' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }} rounded-xl transition-colors group">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('authors.*') ? '' : 'group-hover:text-white transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="font-medium text-sm tracking-wide">Authors</span>
            </a>
            <a href="{{ route('books.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('books.*') ? 'bg-indigo-600 text-white shadow-[0_4px_15px_rgba(79,70,229,0.3)]' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }} rounded-xl transition-colors group">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('books.*') ? '' : 'group-hover:text-white transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <span class="font-medium text-sm tracking-wide">Books</span>
            </a>
            <a href="{{ route('borrowings.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('borrowings.*') ? 'bg-indigo-600 text-white shadow-[0_4px_15px_rgba(79,70,229,0.3)]' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }} rounded-xl transition-colors group">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('borrowings.*') ? '' : 'group-hover:text-white transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                <span class="font-medium text-sm tracking-wide">Borrow Records</span>
            </a>
            <a href="{{ route('categories.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('categories.*') ? 'bg-indigo-600 text-white shadow-[0_4px_15px_rgba(79,70,229,0.3)]' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }} rounded-xl transition-colors group">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('categories.*') ? '' : 'group-hover:text-white transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span class="font-medium text-sm tracking-wide">Categories</span>
            </a>
            <a href="{{ route('members.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('members.*') ? 'bg-indigo-600 text-white shadow-[0_4px_15px_rgba(79,70,229,0.3)]' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }} rounded-xl transition-colors group">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('members.*') ? '' : 'group-hover:text-white transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span class="font-medium text-sm tracking-wide">Members</span>
            </a>
            <a href="{{ route('reports.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('reports.*') ? 'bg-indigo-600 text-white shadow-[0_4px_15px_rgba(79,70,229,0.3)]' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }} rounded-xl transition-colors group mt-6">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('reports.*') ? '' : 'group-hover:text-white transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                <span class="font-medium text-sm tracking-wide">Reports</span>
            </a>
            <a href="{{ route('settings.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('settings.*') ? 'bg-indigo-600 text-white shadow-[0_4px_15px_rgba(79,70,229,0.3)]' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }} rounded-xl transition-colors group">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('settings.*') ? '' : 'group-hover:text-white transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span class="font-medium text-sm tracking-wide">Settings</span>
            </a>
            
            <form method="POST" action="{{ route('logout') }}" class="mt-4 border-t border-slate-700/50 pt-4">
                @csrf
                <button type="submit" class="w-full flex items-center px-4 py-3 hover:bg-slate-800 text-slate-400 hover:text-red-400 rounded-xl transition-colors group">
                    <svg class="w-5 h-5 mr-3 group-hover:text-red-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span class="font-medium text-sm tracking-wide">Logout</span>
                </button>
            </form>
        </nav>

        <!-- Sidebar Promo Card -->
        <div class="px-4 mb-6 hidden lg:block">
            <div class="dark-card p-4 rounded-2xl flex flex-col items-center text-center relative overflow-hidden">
                <div class="w-16 h-16 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-full flex items-center justify-center mb-3">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <h4 class="text-white text-sm font-bold mb-1">Welcome to</h4>
                <p class="text-indigo-400 text-sm font-bold mb-2">Laravel Library</p>
                <p class="text-slate-400 text-xs">Manage your library resources efficiently.</p>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-full overflow-hidden">
        
        <!-- Top Navbar -->
        <header class="h-20 px-8 flex items-center justify-between shrink-0 border-b border-slate-800">
            <div class="flex items-center">
                <button class="text-slate-400 hover:text-white lg:hidden mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
            
            @php
                $searchAction = route('books.index');
                if (request()->routeIs('authors.*')) {
                    $searchAction = route('authors.index');
                } elseif (request()->routeIs('categories.*')) {
                    $searchAction = route('categories.index');
                } elseif (request()->routeIs('members.*')) {
                    $searchAction = route('members.index');
                } elseif (request()->routeIs('borrowings.*')) {
                    $searchAction = route('borrowings.index');
                }
            @endphp
            <form action="{{ $searchAction }}" method="GET" class="flex-1 flex justify-center max-w-lg mx-auto">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" id="globalSearchInput" value="{{ request('search') }}" class="w-full bg-[#1e293b] border border-slate-700 rounded-full py-2 pl-10 pr-4 text-sm text-slate-300 placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="Search by title, author or ISBN...">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <kbd class="hidden sm:inline-block border border-slate-700 rounded px-2 py-0.5 text-xs font-sans text-slate-500">/</kbd>
                    </div>
                </div>
            </form>

            <div class="flex items-center space-x-5">
                <button class="relative text-slate-400 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span class="absolute -top-1 -right-1 block h-3.5 w-3.5 rounded-full bg-indigo-500 border-2 border-[#0f172a] text-[8px] text-white font-bold flex items-center justify-center">3</span>
                </button>
                <div class="flex items-center pl-4 border-l border-slate-700">
                    <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-xs shadow-md">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="ml-2 hidden md:block">
                        <p class="text-white text-xs font-semibold leading-tight">{{ Auth::user()->name ?? 'Administrator' }}</p>
                        <p class="text-slate-400 text-[10px]">Admin</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dashboard Content Scrollable Area -->
        <div class="flex-1 overflow-auto px-8 pb-8 pt-6">
            @yield('content')
        </div>
    </main>

    <!-- Floating Session Flash Alerts -->
    @if(session('success'))
    @php
        $msg = session('success');
        $title = 'Success';
        if (stripos($msg, 'created') !== false || stripos($msg, 'added') !== false) {
            $title = 'Created Successfully';
        } elseif (stripos($msg, 'updated') !== false) {
            $title = 'Updated Successfully';
        } elseif (stripos($msg, 'deleted') !== false) {
            $title = 'Deleted Successfully';
        }
    @endphp
    <div id="adminSuccessToast" class="fixed bottom-6 right-6 z-50 flex items-center px-5 py-4 bg-emerald-950/90 border border-emerald-500/30 text-emerald-200 rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.5)] backdrop-blur-md transform translate-y-0 opacity-100 transition-all duration-300">
        <div class="bg-emerald-500/20 p-2 rounded-xl text-emerald-400 border border-emerald-500/30 mr-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <div>
            <span class="font-bold text-sm block leading-none mb-1">{{ $title }}</span>
            <span class="text-xs text-slate-300">{{ session('success') }}</span>
        </div>
        <button onclick="document.getElementById('adminSuccessToast').remove()" class="ml-4 text-emerald-400 hover:text-emerald-300 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    <script>
        setTimeout(() => {
            const toast = document.getElementById('adminSuccessToast');
            if (toast) {
                toast.classList.add('translate-y-4', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }
        }, 4000);
    </script>
    @endif

    @if(isset($errors) && $errors->any())
    <div id="adminErrorToast" class="fixed bottom-6 right-6 z-50 flex items-center px-5 py-4 bg-rose-950/90 border border-rose-500/30 text-rose-200 rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.5)] backdrop-blur-md transform translate-y-0 opacity-100 transition-all duration-300">
        <div class="bg-rose-500/20 p-2 rounded-xl text-rose-400 border border-rose-500/30 mr-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M3 19h18a1 1 0 00.728-1.686L12.728 3.314a1 1 0 00-1.456 0L2.272 17.314A1 1 0 003 19z"></path></svg>
        </div>
        <div>
            <span class="font-bold text-sm block leading-none mb-1">Operation Failed</span>
            <span class="text-xs text-slate-300">{{ $errors->first() }}</span>
        </div>
        <button onclick="document.getElementById('adminErrorToast').remove()" class="ml-4 text-rose-400 hover:text-rose-300 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    <script>
        setTimeout(() => {
            const toast = document.getElementById('adminErrorToast');
            if (toast) {
                toast.classList.add('translate-y-4', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }
        }, 4000);
    </script>
    @endif

    <script>
        document.addEventListener('keydown', (e) => {
            if (e.key === '/' && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
                e.preventDefault();
                const globalSearch = document.getElementById('globalSearchInput');
                if (globalSearch) {
                    globalSearch.focus();
                    globalSearch.select();
                }
            }
        });
    </script>

</body>
</html>

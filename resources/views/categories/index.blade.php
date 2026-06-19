@extends('layouts.admin')

@section('title', 'Manage Categories - Library Management')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Categories</h1>
            <p class="text-slate-400 mt-1 text-sm">Manage the library's book categories.</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="toggleImportModal()" class="bg-[#1e293b] hover:bg-[#334155] text-indigo-400 hover:text-indigo-300 border border-slate-700 px-4 py-2 rounded-lg font-medium transition-colors flex items-center shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Import Categories
            </button>
            <a href="{{ route('categories.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium shadow-[0_4px_15px_rgba(79,70,229,0.3)] transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Add New Category
            </a>
        </div>
    </div>

    <!-- Data Table -->
    <div class="dark-card rounded-2xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-slate-700/50 flex justify-between items-center bg-slate-800/30">
            <form action="{{ route('categories.index') }}" method="GET" class="relative w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" class="w-full bg-[#0f172a] border border-slate-700 rounded-lg py-1.5 pl-9 pr-4 text-sm text-slate-300 placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="Search categories...">
            </form>
            <div class="text-slate-400 text-sm">
                Showing {{ $categories->count() }} categories
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-slate-400 text-xs uppercase tracking-wider bg-slate-800/20 border-b border-slate-700/80">
                        <th class="px-6 py-4 font-medium">Category Name</th>
                        <th class="px-6 py-4 font-medium">Description</th>
                        <th class="px-6 py-4 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-700/50">
                    @forelse($categories as $category)
                    <tr class="hover:bg-slate-800/30 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-orange-500/20 flex items-center justify-center text-orange-400 mr-4 shrink-0 border border-orange-500/30">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                </div>
                                <div class="font-bold text-slate-200">{{ $category->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-400 text-xs">
                            <span class="line-clamp-2 max-w-xs">{{ $category->description ?? 'No description provided.' }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('categories.edit', $category) }}" class="p-2 text-slate-400 hover:text-indigo-400 hover:bg-indigo-400/10 rounded transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this category?');">
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
                        <td colspan="3" class="py-8 text-center text-slate-500">No categories found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Import Modal -->
    <div id="importCategoriesModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="toggleImportModal()"></div>
        <!-- Modal Card -->
        <div class="dark-card rounded-2xl w-full max-w-lg p-6 relative z-10 shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-slate-700">
            <!-- Close Button -->
            <button onclick="toggleImportModal()" class="absolute top-4 right-4 text-slate-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <h3 class="text-xl font-bold text-white mb-2 flex items-center">
                <svg class="w-6 h-6 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Import Categories via CSV
            </h3>
            <p class="text-slate-400 text-sm mb-4">Bulk import categories into your library catalog. Existing category names will be skipped.</p>
            
            <!-- Sample format box -->
            <div class="bg-[#0f172a] border border-slate-800 rounded-xl p-4 mb-6">
                <span class="text-xs text-indigo-300 font-bold block mb-2">Required CSV Column Headers</span>
                <code class="text-xs text-slate-300 font-mono block overflow-x-auto whitespace-nowrap bg-slate-900/60 p-2 rounded">
                    name, description
                </code>
                <span class="text-[10px] text-slate-500 block mt-2">Example: "Science Fiction, Books containing futuristic science and technology concepts."</span>
            </div>

            <!-- Form -->
            <form action="{{ route('categories.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-slate-300 text-sm font-medium mb-2">Select CSV File</label>
                    <input type="file" name="csv_file" accept=".csv,text/plain" required class="w-full bg-[#0f172a] border border-slate-700 rounded-xl p-3 text-sm text-slate-300 focus:outline-none focus:border-indigo-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 file:transition-colors">
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-700/50">
                    <button type="button" onclick="toggleImportModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-sm font-medium transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-colors flex items-center shadow-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        Upload and Import
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleImportModal() {
            const modal = document.getElementById('importCategoriesModal');
            if (modal) {
                modal.classList.toggle('hidden');
            }
        }
    </script>
    @endpush
@endsection

@extends('layouts.admin')

@section('title', 'Manage Authors - Library Management')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Authors</h1>
            <p class="text-slate-400 mt-1 text-sm">Manage the list of authors in your catalog.</p>
        </div>
        <a href="{{ route('authors.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium shadow-[0_4px_15px_rgba(79,70,229,0.3)] transition-colors flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            Add New Author
        </a>
    </div>

    <!-- Data Table -->
    <div class="dark-card rounded-2xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-slate-700/50 flex justify-between items-center bg-slate-800/30">
            <div class="relative w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" class="w-full bg-[#0f172a] border border-slate-700 rounded-lg py-1.5 pl-9 pr-4 text-sm text-slate-300 placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="Search authors...">
            </div>
            <div class="text-slate-400 text-sm">
                Showing {{ $authors->count() }} authors
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-slate-400 text-xs uppercase tracking-wider bg-slate-800/20 border-b border-slate-700/80">
                        <th class="px-6 py-4 font-medium">Author Name</th>
                        <th class="px-6 py-4 font-medium">Biography</th>
                        <th class="px-6 py-4 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-700/50">
                    @forelse($authors as $author)
                    <tr class="hover:bg-slate-800/30 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center text-slate-300 mr-4 shrink-0 font-bold border border-slate-600">
                                    {{ substr($author->name, 0, 1) }}
                                </div>
                                <div class="font-bold text-slate-200">{{ $author->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-400 text-xs">
                            <span class="line-clamp-2 max-w-xs">{{ $author->bio ?? 'No biography provided.' }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('authors.edit', $author) }}" class="p-2 text-slate-400 hover:text-indigo-400 hover:bg-indigo-400/10 rounded transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <form action="{{ route('authors.destroy', $author) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this author?');">
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
                        <td colspan="3" class="py-8 text-center text-slate-500">No authors found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

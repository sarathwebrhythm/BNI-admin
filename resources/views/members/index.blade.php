@extends('layouts.admin')

@section('title', 'Member Directory')
@section('page_title', 'Member Directory')

@section('content')
<div class="space-y-6">

    <!-- Directory Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-slate-200">Registered Members</h2>
            <p class="text-xs text-slate-400 mt-1">Browse, search, and filter the roster of BNI members.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <!-- Download Template -->
            <a href="{{ route('admin.export-template') }}" class="inline-flex items-center px-4 py-2.5 text-xs font-semibold text-slate-300 hover:text-white bg-slate-900 border border-slate-800 hover:border-slate-700 rounded-xl transition duration-200">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Template
            </a>

            <!-- Export Excel -->
            <a href="{{ route('admin.export', request()->query()) }}" class="inline-flex items-center px-4 py-2.5 text-xs font-semibold text-slate-300 hover:text-white bg-slate-900 border border-slate-800 hover:border-slate-700 rounded-xl transition duration-200">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export Excel
            </a>

            <!-- Add Member -->
            <a href="{{ route('admin.members.create') }}" class="inline-flex items-center px-4 py-2.5 text-xs font-semibold text-white bg-brand hover:bg-brand-dark rounded-xl shadow-md shadow-brand/20 hover:shadow-brand/30 transition duration-200">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Member
            </a>
        </div>
    </div>

    <!-- Search & Filters Card -->
    <div class="bg-slate-900 border border-slate-800/80 rounded-2xl p-6 shadow-md">
        <form method="GET" action="{{ route('admin.members.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <!-- Search Input -->
            <div class="md:col-span-2">
                <label for="search" class="block text-xs font-medium text-slate-400 mb-1.5">Search Members</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" id="search" name="search" value="{{ $search }}" placeholder="Search by name, email, phone, company, chapter..."
                        class="block w-full pl-9 pr-3 py-2 border border-slate-800 rounded-xl bg-slate-950/60 placeholder-slate-500 text-white focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand text-sm transition-all">
                </div>
            </div>

            <!-- Filter Status -->
            <div>
                <label for="status" class="block text-xs font-medium text-slate-400 mb-1.5">Status</label>
                <select id="status" name="status"
                    class="block w-full px-3 py-2 border border-slate-800 rounded-xl bg-slate-950/60 text-slate-300 focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand text-sm transition-all cursor-pointer">
                    <option value="">All Statuses</option>
                    <option value="active" {{ ($filters['status'] ?? '') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <!-- Filter Chapter & Buttons -->
            <div class="flex items-center gap-2">
                <div class="flex-1">
                    <label for="chapter" class="block text-xs font-medium text-slate-400 mb-1.5">Chapter</label>
                    <select id="chapter" name="chapter"
                        class="block w-full px-3 py-2 border border-slate-800 rounded-xl bg-slate-950/60 text-slate-300 focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand text-sm transition-all cursor-pointer">
                        <option value="">All Chapters</option>
                        @foreach($chapters as $ch)
                            <option value="{{ $ch }}" {{ ($filters['chapter'] ?? '') === $ch ? 'selected' : '' }}>{{ $ch }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="px-3.5 py-2.5 bg-brand hover:bg-brand-dark text-white rounded-xl text-sm font-semibold transition shrink-0">
                        Filter
                    </button>
                    @if($search || !empty($filters['status']) || !empty($filters['chapter']))
                        <a href="{{ route('admin.members.index') }}" class="px-3 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-sm font-semibold transition shrink-0" title="Clear Filters">
                            Clear
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Members Table Card -->
    <div class="bg-slate-900 border border-slate-800/80 rounded-2xl shadow-lg overflow-hidden">
        @if($members->isEmpty())
            <div class="text-center py-16 px-4">
                <svg class="mx-auto h-12 w-12 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <h3 class="mt-4 text-sm font-semibold text-slate-300">No members found</h3>
                <p class="mt-2 text-xs text-slate-500 max-w-sm mx-auto">Try refining your search query, clearing your filters, or registering a new member.</p>
                <div class="mt-6 flex justify-center gap-4">
                    <a href="{{ route('admin.members.create') }}" class="inline-flex items-center px-4 py-2.5 text-xs font-semibold text-white bg-brand hover:bg-brand-dark rounded-xl transition">
                        Add Member
                    </a>
                    <a href="{{ route('admin.import.show') }}" class="inline-flex items-center px-4 py-2.5 text-xs font-semibold text-slate-300 bg-slate-800 hover:bg-slate-750 rounded-xl border border-slate-700 transition">
                        Import Excel
                    </a>
                </div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-850">
                    <thead>
                        <tr class="bg-slate-900/60">
                            <th class="px-6 py-3.5 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3.5 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Company / Role</th>
                            <th class="px-6 py-3.5 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-3.5 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Chapter</th>
                            <th class="px-6 py-3.5 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3.5 text-right text-xs font-medium text-slate-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-850 text-sm">
                        @foreach($members as $member)
                            <tr class="hover:bg-slate-800/20 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-9 w-9 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-slate-300 mr-3 shadow-inner">
                                            {{ substr($member->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.members.show', $member->id) }}" class="font-medium text-slate-200 hover:text-brand-light transition-colors">
                                                {{ $member->name }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-slate-200 font-medium">{{ $member->company ?: '—' }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $member->designation ?: '—' }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-slate-300">{{ $member->email }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $member->phone ?: '—' }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-300">
                                    {{ $member->chapter ?: '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($member->status === 'active')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-800 text-slate-400 border border-slate-700">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                                    <div class="flex items-center justify-end space-x-3">
                                        <!-- View -->
                                        <a href="{{ route('admin.members.show', $member->id) }}" class="text-brand-light hover:text-brand-light transition" title="View details">
                                            View
                                        </a>
                                        <!-- Edit -->
                                        <a href="{{ route('admin.members.edit', $member->id) }}" class="text-amber-400 hover:text-amber-300 transition" title="Edit details">
                                            Edit
                                        </a>
                                        <!-- Delete -->
                                        <form action="{{ route('admin.members.destroy', $member->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this member?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-400 hover:text-rose-300 transition bg-transparent border-0 cursor-pointer">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination Controls -->
            <div class="px-6 py-4 border-t border-slate-850">
                {{ $members->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
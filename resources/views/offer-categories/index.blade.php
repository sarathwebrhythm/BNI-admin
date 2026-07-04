@extends('layouts.admin')

@section('title', 'Offer Categories')
@section('page_title', 'Offer Categories')

@section('content')

<div class="space-y-6">


    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-slate-200">Offer Categories</h2>
            <p class="text-xs text-slate-400 mt-1">
                Manage offer categories such as Restaurant, Travel, Fitness, Shopping, etc.
            </p>
        </div>

        <a href="{{ route('admin.offer-categories.create') }}"
            class="inline-flex items-center px-4 py-2.5 text-xs font-semibold text-white bg-brand hover:bg-brand-dark rounded-xl shadow-md shadow-brand/20 hover:shadow-brand/30 transition duration-200">
            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4v16m8-8H4" />
            </svg>
            Add Category
        </a>
    </div>

    <!-- Search Bar -->
    <div class="bg-slate-900 border border-slate-800/80 rounded-2xl p-4 shadow-md">
        <form method="GET" action="{{ route('admin.offer-categories.index') }}" id="search-form">
            <!-- Keep filter values when searching -->
            <input type="hidden" name="status" value="{{ $filters['status'] ?? '' }}">

            <div class="flex gap-3">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ $search }}"
                        placeholder="Search category..."
                        class="block w-full px-4 py-2.5 border border-slate-800 rounded-xl bg-slate-950/60 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand text-sm">
                </div>
                <button type="submit"
                    class="px-5 py-2.5 bg-brand hover:bg-brand-dark text-white rounded-xl text-sm font-semibold transition whitespace-nowrap">
                    Search
                </button>
                @if($search)
                <a href="{{ route('admin.offer-categories.index', array_filter(['status' => $filters['status'] ?? ''])) }}"
                    class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-sm font-semibold transition whitespace-nowrap">
                    Clear
                </a>
                @endif

                <!-- Filter Toggle Button -->
                <button type="button" onclick="toggleFilters()"
                    class="flex items-center gap-2 px-4 py-2.5 border rounded-xl text-sm font-semibold transition whitespace-nowrap
                    {{ !empty($filters['status']) ? 'border-brand text-brand bg-brand/10' : 'border-slate-700 text-slate-300 hover:border-slate-600 hover:text-slate-200 bg-slate-800/50' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                    </svg>
                    Filters
                    @if(!empty($filters['status']))
                    <span class="w-2 h-2 rounded-full bg-brand"></span>
                    @endif
                </button>
            </div>
        </form>
    </div>

    <!-- Filter Panel (toggle) -->
    <div id="filter-panel" class="{{ !empty($filters['status']) ? '' : 'hidden' }}">
        <form method="GET" action="{{ route('admin.offer-categories.index') }}" id="filter-form">
            <!-- Keep search value when filtering -->
            <input type="hidden" name="search" value="{{ $search }}">

            <div class="bg-slate-900 border border-slate-800/80 rounded-2xl p-5 shadow-md">
                <div class="flex flex-col md:flex-row gap-4 items-end">

                    <!-- Status -->
                    <div class="w-full md:w-56">
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Status</label>
                        <select name="status"
                            class="block w-full px-3 py-2.5 border border-slate-800 rounded-xl bg-slate-950/60 text-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
                            <option value="">All Status</option>
                            <option value="active"   {{ ($filters['status'] ?? '') == 'active'   ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ ($filters['status'] ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-2">
                        <button type="submit"
                            class="px-5 py-2.5 bg-brand hover:bg-brand-dark text-white rounded-xl text-sm font-semibold transition">
                            Apply
                        </button>
                        @if(!empty($filters['status']))
                        <a href="{{ route('admin.offer-categories.index', ['search' => $search]) }}"
                            class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-sm font-semibold transition">
                            Clear Filters
                        </a>
                        @endif
                    </div>

                </div>
            </div>
        </form>
    </div>

    <!-- Active Filters Summary -->
    @if(!empty($filters['status']))
    <div class="flex items-center gap-2 flex-wrap">
        <span class="text-xs text-slate-500">Active filters:</span>
        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-brand/10 border border-brand/20 text-brand text-xs rounded-full">
            Status: {{ ucfirst($filters['status']) }}
        </span>
    </div>
    @endif

    <!-- Categories Table -->
    <div class="bg-slate-900 border border-slate-800/80 rounded-2xl shadow-lg overflow-hidden">

        @if($categories->isEmpty())

        <div class="text-center py-16">
            <h3 class="text-sm font-semibold text-slate-300">
                No categories found
            </h3>

            <p class="mt-2 text-xs text-slate-500">
                Create your first offer category.
            </p>

            <div class="mt-6">
                <a href="{{ route('admin.offer-categories.create') }}"
                    class="inline-flex items-center px-4 py-2.5 text-xs font-semibold text-white bg-brand hover:bg-brand-dark rounded-xl transition">
                    Add Category
                </a>
            </div>
        </div>

        @else

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-850">
                <thead>
                    <tr class="bg-slate-900/60">
                        <th class="px-6 py-3.5 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                            Category Name
                        </th>

                        <th class="px-6 py-3.5 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                            Status
                        </th>

                        <th class="px-6 py-3.5 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                            Icon
                        </th>
                        <th class="px-6 py-3.5 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                            Display Order
                        </th>

                        <th class="px-6 py-3.5 text-right text-xs font-medium text-slate-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-850 text-sm">
                    @foreach($categories as $category)
                    <tr class="hover:bg-slate-800/20 transition-colors">

                        <td class="px-6 py-4 text-slate-200 font-medium">
                            {{ $category->name }}
                        </td>

                        <td class="px-6 py-4">
                            @if($category->status)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                Active
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-800 text-slate-400 border border-slate-700">
                                Inactive
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($category->icon)
                            <img
                                src="{{ asset('storage/' . $category->icon) }}"
                                alt="{{ $category->name }}"
                                class="w-6 h-6 object-contain rounded-lg border border-slate-700 bg-white p-1">
                            @else
                            <span class="text-slate-500 text-sm">No Icon</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-slate-300">
                            {{ $category->order }}
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-3">

                                <a href="{{ route('admin.offer-categories.show', $category->id) }}"
                                    class="text-brand-light hover:text-brand-light">
                                    View
                                </a>

                                <a href="{{ route('admin.offer-categories.edit', $category->id) }}"
                                    class="text-amber-400 hover:text-amber-300">
                                    Edit
                                </a>

                                <form action="{{ route('admin.offer-categories.destroy', $category->id) }}"
                                    method="POST"
                                    class="inline"
                                    onsubmit="return confirm('Are you sure you want to delete this category?');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="text-rose-400 hover:text-rose-300 bg-transparent border-0 cursor-pointer">
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

        <div class="px-6 py-4 border-t border-slate-850">
            {{ $categories->links() }}
        </div>

        @endif

    </div>


</div>

<script>
function toggleFilters() {
    const panel = document.getElementById('filter-panel');
    panel.classList.toggle('hidden');
}
</script>

@endsection
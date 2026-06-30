@extends('layouts.admin')

@section('title', 'Offers')
@section('page_title', 'Offers')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-slate-200">Offers</h2>
            <p class="text-xs text-slate-400 mt-1">View and manage all member submitted offers.</p>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="bg-slate-900 border border-slate-800/80 rounded-2xl p-4 shadow-md">
        <form method="GET" action="{{ route('admin.offers.index') }}" id="search-form">
            <!-- Keep filter values when searching -->
            <input type="hidden" name="status" value="{{ $filters['status'] ?? '' }}">
            <input type="hidden" name="category" value="{{ $filters['category'] ?? '' }}">

            <div class="flex gap-3">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ $search }}"
                        placeholder="Search by discount, member, company..."
                        class="block w-full px-4 py-2.5 border border-slate-800 rounded-xl bg-slate-950/60 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand text-sm">
                </div>
                <button type="submit"
                    class="px-5 py-2.5 bg-brand hover:bg-brand-dark text-white rounded-xl text-sm font-semibold transition whitespace-nowrap">
                    Search
                </button>
                @if($search)
                <a href="{{ route('admin.offers.index', array_filter(['status' => $filters['status'] ?? '', 'category' => $filters['category'] ?? ''])) }}"
                    class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-sm font-semibold transition whitespace-nowrap">
                    Clear
                </a>
                @endif

                <!-- Filter Toggle Button -->
                <button type="button" onclick="toggleFilters()"
                    class="flex items-center gap-2 px-4 py-2.5 border rounded-xl text-sm font-semibold transition whitespace-nowrap
                    {{ !empty($filters['status']) || !empty($filters['category']) ? 'border-brand text-brand bg-brand/10' : 'border-slate-700 text-slate-300 hover:border-slate-600 hover:text-slate-200 bg-slate-800/50' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                    </svg>
                    Filters
                    @if(!empty($filters['status']) || !empty($filters['category']))
                    <span class="w-2 h-2 rounded-full bg-brand"></span>
                    @endif
                </button>
            </div>
        </form>
    </div>

    <!-- Filter Panel (toggle) -->
    <div id="filter-panel" class="{{ !empty($filters['status']) || !empty($filters['category']) ? '' : 'hidden' }}">
        <form method="GET" action="{{ route('admin.offers.index') }}" id="filter-form">
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
                            <option value="pending"  {{ ($filters['status'] ?? '') == 'pending'  ? 'selected' : '' }}>Pending</option>
                            <option value="active"   {{ ($filters['status'] ?? '') == 'active'   ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ ($filters['status'] ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="rejected" {{ ($filters['status'] ?? '') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <!-- Category -->
                    <div class="w-full md:w-56">
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Category</label>
                        <select name="category"
                            class="block w-full px-3 py-2.5 border border-slate-800 rounded-xl bg-slate-950/60 text-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ ($filters['category'] ?? '') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-2">
                        <button type="submit"
                            class="px-5 py-2.5 bg-brand hover:bg-brand-dark text-white rounded-xl text-sm font-semibold transition">
                            Apply
                        </button>
                        @if(!empty($filters['status']) || !empty($filters['category']))
                        <a href="{{ route('admin.offers.index', ['search' => $search]) }}"
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
    @if(!empty($filters['status']) || !empty($filters['category']))
    <div class="flex items-center gap-2 flex-wrap">
        <span class="text-xs text-slate-500">Active filters:</span>
        @if(!empty($filters['status']))
        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-brand/10 border border-brand/20 text-brand text-xs rounded-full">
            Status: {{ ucfirst($filters['status']) }}
        </span>
        @endif
        @if(!empty($filters['category']))
        @php $cat = $categories->firstWhere('id', $filters['category']); @endphp
        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-brand/10 border border-brand/20 text-brand text-xs rounded-full">
            Category: {{ $cat->name ?? $filters['category'] }}
        </span>
        @endif
    </div>
    @endif

    <!-- Offers Table -->
    <div class="bg-slate-900 border border-slate-800/80 rounded-2xl shadow-lg overflow-hidden">

        @if($offers->isEmpty())
        <div class="text-center py-16">
            <h3 class="text-sm font-semibold text-slate-300">No offers found</h3>
            <p class="mt-2 text-xs text-slate-500">No offers match your search or filters.</p>
        </div>
        @else

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-850">
                <thead>
                    <tr class="bg-slate-900/60">
                        <th class="px-6 py-3.5 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Member</th>
                        <th class="px-6 py-3.5 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Discount</th>
                        <th class="px-6 py-3.5 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3.5 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Valid</th>
                        <th class="px-6 py-3.5 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-right text-xs font-medium text-slate-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850 text-sm">
                    @foreach($offers as $offer)
                    <tr class="hover:bg-slate-800/20 transition-colors">

                        <td class="px-6 py-4">
                            <p class="text-slate-200 font-medium">{{ $offer->member->name ?? '—' }}</p>
                            <p class="text-slate-500 text-xs mt-0.5">{{ $offer->member->company ?? '—' }}</p>
                        </td>

                        <td class="px-6 py-4">
                            <p class="text-slate-200 font-semibold">{{ $offer->discount }}</p>
                            @if($offer->title)
                            <p class="text-slate-500 text-xs mt-0.5">{{ $offer->title }}</p>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-slate-300">
                            {{ $offer->category->name ?? '—' }}
                        </td>

                        <td class="px-6 py-4">
                            <p class="text-slate-300 text-xs">{{ $offer->start_date->format('d M Y') }}</p>
                            <p class="text-slate-500 text-xs">to {{ $offer->end_date->format('d M Y') }}</p>
                        </td>

                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'pending'  => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                    'active'   => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                    'inactive' => 'bg-slate-800 text-slate-400 border-slate-700',
                                    'rejected' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                ];
                                $color = $statusColors[$offer->status] ?? 'bg-slate-800 text-slate-400 border-slate-700';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $color }}">
                                {{ ucfirst($offer->status) }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">

                                <a href="{{ route('admin.offers.show', $offer->id) }}"
                                    class="text-brand-light hover:text-brand text-xs font-medium">
                                    View
                                </a>

                                @if($offer->status === 'pending')
                                <form action="{{ route('admin.offers.update', $offer->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="active">
                                    <button type="submit" class="text-emerald-400 hover:text-emerald-300 text-xs font-medium bg-transparent border-0 cursor-pointer">
                                        Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.offers.update', $offer->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="text-rose-400 hover:text-rose-300 text-xs font-medium bg-transparent border-0 cursor-pointer">
                                        Reject
                                    </button>
                                </form>
                                @endif

                                @if($offer->status === 'active')
                                <form action="{{ route('admin.offers.update', $offer->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="inactive">
                                    <button type="submit" class="text-amber-400 hover:text-amber-300 text-xs font-medium bg-transparent border-0 cursor-pointer">
                                        Deactivate
                                    </button>
                                </form>
                                @endif

                                @if($offer->status === 'inactive' || $offer->status === 'rejected')
                                <form action="{{ route('admin.offers.update', $offer->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="active">
                                    <button type="submit" class="text-emerald-400 hover:text-emerald-300 text-xs font-medium bg-transparent border-0 cursor-pointer">
                                        Activate
                                    </button>
                                </form>
                                @endif

                                <form action="{{ route('admin.offers.destroy', $offer->id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Are you sure you want to delete this offer?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-rose-400 hover:text-rose-300 text-xs font-medium bg-transparent border-0 cursor-pointer">
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
            {{ $offers->links() }}
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
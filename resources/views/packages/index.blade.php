@extends('layouts.admin')

@section('title', 'Packages')
@section('page_title', 'Packages')

@section('content')

<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-slate-200">Packages</h2>
            <p class="text-xs text-slate-400 mt-1">
                Manage membership packages and offer limits.
            </p>
        </div>

        <a href="{{ route('admin.packages.create') }}"
            class="inline-flex items-center px-4 py-2.5 text-xs font-semibold text-white bg-brand hover:bg-brand-dark rounded-xl shadow-md shadow-brand/20 hover:shadow-brand/30 transition duration-200">
            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4v16m8-8H4" />
            </svg>
            Add Package
        </a>
    </div>

    <!-- Search -->
    <div class="bg-slate-900 border border-slate-800/80 rounded-2xl p-6 shadow-md">
        <form method="GET" action="{{ route('admin.packages.index') }}">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label for="search" class="block text-xs font-medium text-slate-400 mb-1.5">
                        Search Package
                    </label>

                    <input
                        type="text"
                        id="search"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search package..."
                        class="block w-full px-3 py-2 border border-slate-800 rounded-xl bg-slate-950/60 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand text-sm">
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="px-4 py-2 bg-brand hover:bg-brand-dark text-white rounded-xl text-sm font-semibold transition">
                        Search
                    </button>

                    @if($search)
                    <a href="{{ route('admin.packages.index') }}"
                        class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-sm font-semibold transition">
                        Clear
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Packages Table -->
    <div class="bg-slate-900 border border-slate-800/80 rounded-2xl shadow-lg overflow-hidden">

        @if($packages->isEmpty())

        <div class="text-center py-16">
            <h3 class="text-sm font-semibold text-slate-300">
                No packages found
            </h3>

            <p class="mt-2 text-xs text-slate-500">
                Create your first package.
            </p>

            <div class="mt-6">
                <a href="{{ route('admin.packages.create') }}"
                    class="inline-flex items-center px-4 py-2.5 text-xs font-semibold text-white bg-brand hover:bg-brand-dark rounded-xl transition">
                    Add Package
                </a>
            </div>
        </div>

        @else

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-850">
                <thead>
                    <tr class="bg-slate-900/60">
                        <th class="px-6 py-3.5 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                            Package Name
                        </th>

                        <th class="px-6 py-3.5 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                            Price
                        </th>

                        <th class="px-6 py-3.5 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                            Offer Limit
                        </th>

                        <th class="px-6 py-3.5 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                            Status
                        </th>

                        <th class="px-6 py-3.5 text-right text-xs font-medium text-slate-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-850 text-sm">
                    @foreach($packages as $package)
                    <tr class="hover:bg-slate-800/20 transition-colors">

                        <td class="px-6 py-4 text-slate-200 font-medium">
                            {{ $package->name }}
                        </td>

                        <td class="px-6 py-4 text-slate-300">
                            ₹{{ number_format($package->price, 2) }}
                        </td>

                        <td class="px-6 py-4 text-slate-300">
                            {{ $package->offer_limit >= 999999 ? 'Unlimited' : $package->offer_limit }}
                        </td>

                        <td class="px-6 py-4">
                            @if($package->status)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                Active
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-800 text-slate-400 border border-slate-700">
                                Inactive
                            </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-3">

                                <a href="{{ route('admin.packages.show', $package->id) }}"
                                    class="text-brand-light hover:text-brand">
                                    View
                                </a>

                                <a href="{{ route('admin.packages.edit', $package->id) }}"
                                    class="text-amber-400 hover:text-amber-300">
                                    Edit
                                </a>

                                <form action="{{ route('admin.packages.destroy', $package->id) }}"
                                    method="POST"
                                    class="inline"
                                    onsubmit="return confirm('Are you sure you want to delete this package?');">
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
            {{ $packages->links() }}
        </div>

        @endif

    </div>

</div>

@endsection
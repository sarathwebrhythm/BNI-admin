@extends('layouts.admin')

@section('title', 'View Package')
@section('page_title', 'View Package')

@section('content')

<div class="space-y-6">

    <!-- Header navigation -->
    <div>
        <a href="{{ route('admin.packages.index') }}"
            class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-slate-200 transition">
            &larr; Back to Packages
        </a>
    </div>

    <!-- Package Details -->
    <div class="bg-slate-900 border border-slate-800/80 rounded-2xl shadow-lg p-8 max-w-4xl">

        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-850">
            <h3 class="text-lg font-semibold text-slate-200">
                Package Details
            </h3>

            <a href="{{ route('admin.packages.edit', $package->id) }}"
                class="px-4 py-2 bg-brand hover:bg-brand-dark text-white rounded-xl text-sm font-semibold transition">
                Edit Package
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Package Name -->
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-500 mb-1">
                    Package Name
                </p>

                <p class="text-slate-200 font-medium">
                    {{ $package->name }}
                </p>
            </div>

            <!-- Price -->
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-500 mb-1">
                    Price
                </p>

                <p class="text-slate-200 font-medium">
                    ₹{{ number_format($package->price, 2) }}
                </p>
            </div>

            <!-- Offer Limit -->
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-500 mb-1">
                    Offer Limit
                </p>

                <p class="text-slate-200 font-medium">
                    {{ $package->offer_limit >= 999999 ? 'Unlimited' : $package->offer_limit }}
                </p>
            </div>

            <!-- Status -->
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-500 mb-1">
                    Status
                </p>

                @if($package->status)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                        Active
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-800 text-slate-400 border border-slate-700">
                        Inactive
                    </span>
                @endif
            </div>

            <!-- Description -->
            <div class="md:col-span-2">
                <p class="text-xs uppercase tracking-wide text-slate-500 mb-1">
                    Description
                </p>

                <p class="text-slate-300 whitespace-pre-line">
                    {{ $package->description ?: 'No description added.' }}
                </p>
            </div>

            <!-- Created At -->
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-500 mb-1">
                    Created At
                </p>

                <p class="text-slate-300">
                    {{ $package->created_at->format('d M Y h:i A') }}
                </p>
            </div>

            <!-- Updated At -->
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-500 mb-1">
                    Updated At
                </p>

                <p class="text-slate-300">
                    {{ $package->updated_at->format('d M Y h:i A') }}
                </p>
            </div>

        </div>

        <!-- Footer Actions -->
        <div class="pt-6 mt-6 border-t border-slate-850 flex items-center justify-end gap-3">

            <a href="{{ route('admin.packages.index') }}"
                class="px-4 py-2 border border-slate-800 hover:border-slate-700 text-slate-300 rounded-xl text-sm font-semibold transition">
                Back
            </a>

            <a href="{{ route('admin.packages.edit', $package->id) }}"
                class="px-5 py-2 bg-brand hover:bg-brand-dark text-white rounded-xl text-sm font-semibold transition">
                Edit Package
            </a>

        </div>

    </div>

</div>

@endsection
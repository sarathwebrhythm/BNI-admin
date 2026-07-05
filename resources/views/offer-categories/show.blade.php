@extends('layouts.admin')

@section('title', 'View Offer Category')
@section('page_title', 'View Offer Category')

@section('content')

<div class="space-y-6">


    <!-- Header navigation -->
    <div>
        <a href="{{ route('admin.offer-categories.index') }}"
            class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-slate-200 transition">
            &larr; Back to Categories
        </a>
    </div>

    <!-- Category Details -->
    <div class="bg-slate-900 border border-slate-800/80 rounded-2xl shadow-lg p-8 max-w-3xl">

        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-850">
            <h3 class="text-lg font-semibold text-slate-200">
                Offer Category Details
            </h3>

            <a href="{{ route('admin.offer-categories.edit', $category->id) }}"
                class="px-4 py-2 bg-brand hover:bg-brand-dark text-white rounded-xl text-sm font-semibold transition">
                Edit Category
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Category Name -->
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-500 mb-1">
                    Category Name
                </p>

                <p class="text-slate-200 font-medium">
                    {{ $category->name }}
                </p>
            </div>

            <!-- Category Icon -->
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-500 mb-1">
                    Category Icon
                </p>

                @if($category->icon)
                <div class="flex items-center gap-3">
                    <img
                        src="{{ asset('storage/' . $category->icon) }}"
                        alt="{{ $category->name }}"
                        class="w-14 h-14 rounded-xl border border-slate-700 object-contain bg-slate-950 p-2">

                    <a href="{{ asset('storage/' . $category->icon) }}"
                        target="_blank"
                        class="text-brand hover:text-brand-dark text-sm">
                        View Icon
                    </a>
                </div>
                @else
                <span class="text-slate-500 text-sm">No icon uploaded</span>
                @endif
            </div>

            <!-- Status -->
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-500 mb-1">
                    Status
                </p>

                @if($category->status)
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    Active
                </span>
                @else
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-800 text-slate-400 border border-slate-700">
                    Inactive
                </span>
                @endif
            </div>

            <!-- Created At -->
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-500 mb-1">
                    Created At
                </p>

                <p class="text-slate-300">
                    {{ $category->created_at->format('d M Y h:i A') }}
                </p>
            </div>

            <!-- Updated At -->
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-500 mb-1">
                    Updated At
                </p>

                <p class="text-slate-300">
                    {{ $category->updated_at->format('d M Y h:i A') }}
                </p>
            </div>

        </div>

        <!-- Footer Actions -->
        <div class="pt-6 mt-6 border-t border-slate-850 flex items-center justify-end gap-3">

            <a href="{{ route('admin.offer-categories.index') }}"
                class="px-4 py-2 border border-slate-800 hover:border-slate-700 text-slate-300 rounded-xl text-sm font-semibold transition">
                Back
            </a>

            <a href="{{ route('admin.offer-categories.edit', $category->id) }}"
                class="px-5 py-2 bg-brand hover:bg-brand-dark text-white rounded-xl text-sm font-semibold transition">
                Edit Category
            </a>

        </div>

    </div>


</div>
@endsection
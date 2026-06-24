@extends('layouts.admin')

@section('title', 'Edit Package')
@section('page_title', 'Edit Package')

@section('content')
<div class="space-y-6">

    <!-- Header navigation -->
    <div>
        <a href="{{ route('admin.packages.index') }}"
            class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-slate-200 transition">
            &larr; Back to Packages
        </a>
    </div>

    <!-- Edit Package Form -->
    <div class="bg-slate-900 border border-slate-800/80 rounded-2xl shadow-lg p-8 max-w-5xl">
        <h3 class="text-lg font-semibold text-slate-200 mb-6 pb-3 border-b border-slate-850">
            Edit Package
        </h3>

        <form action="{{ route('admin.packages.update', $package->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Package Name + Price -->
            <div class="flex flex-col md:flex-row gap-6">

                <div class="flex-1">
                    <label for="name" class="block text-sm font-medium text-slate-300">
                        Package Name <span class="text-rose-500">*</span>
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $package->name) }}"
                        required
                        class="mt-1.5 block w-full px-3 py-2 border border-slate-850 rounded-xl bg-slate-950/60 text-white">
                </div>

                <div class="flex-1">
                    <label for="price" class="block text-sm font-medium text-slate-300">
                        Price <span class="text-rose-500">*</span>
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        id="price"
                        name="price"
                        value="{{ old('price', $package->price) }}"
                        required
                        class="mt-1.5 block w-full px-3 py-2 border border-slate-850 rounded-xl bg-slate-950/60 text-white">
                </div>

            </div>

            <!-- Offer Limit + Status -->
            <div class="flex flex-col md:flex-row gap-6">

                <div class="flex-1">
                    <label for="offer_limit" class="block text-sm font-medium text-slate-300">
                        Offer Limit <span class="text-rose-500">*</span>
                    </label>

                    <input
                        type="number"
                        id="offer_limit"
                        name="offer_limit"
                        min="1"
                        value="{{ old('offer_limit', $package->offer_limit) }}"
                        required
                        class="mt-1.5 block w-full px-3 py-2 border border-slate-850 rounded-xl bg-slate-950/60 text-white">
                </div>

                <div class="flex-1">
                    <label for="status" class="block text-sm font-medium text-slate-300">
                        Status
                    </label>

                    <select
                        id="status"
                        name="status"
                        class="mt-1.5 block w-full px-3 py-2 border border-slate-850 rounded-xl bg-slate-950/60 text-slate-300">

                        <option value="1" {{ old('status', $package->status) == 1 ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="0" {{ old('status', $package->status) == 0 ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>
                </div>

            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-slate-300">
                    Description
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    class="mt-1.5 block w-full px-3 py-2 border border-slate-850 rounded-xl bg-slate-950/60 text-white">{{ old('description', $package->description) }}</textarea>
            </div>

            <!-- Submit buttons -->
            <div class="pt-4 border-t border-slate-850 flex items-center justify-end space-x-3">

                <a href="{{ route('admin.packages.index') }}"
                    class="px-4 py-2 border border-slate-800 hover:border-slate-700 text-slate-300 rounded-xl text-sm font-semibold transition">
                    Cancel
                </a>

                <button
                    type="submit"
                    class="px-5 py-2 bg-brand hover:bg-brand-dark text-white rounded-xl text-sm font-semibold shadow-md shadow-brand/20 transition cursor-pointer">
                    Update Package
                </button>

            </div>

        </form>
    </div>

</div>
@endsection
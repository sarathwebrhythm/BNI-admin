@extends('layouts.admin')

@section('title', 'Add Offer Category')
@section('page_title', 'Add Offer Category')

@section('content')
<div class="space-y-6">

    <!-- Header navigation -->
    <div>
        <a href="{{ route('admin.offer-categories.index') }}"
            class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-slate-200 transition">
            &larr; Back to Categories
        </a>
    </div>

    <!-- Create Category Form -->
    <div class="bg-slate-900 border border-slate-800/80 rounded-2xl shadow-lg p-8 max-w-5xl">
        <h3 class="text-lg font-semibold text-slate-200 mb-6 pb-3 border-b border-slate-850">
            Offer Category Details
        </h3>



        <form action="{{ route('admin.offer-categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Name + Order -->
             <div class="flex flex-col md:flex-row gap-6">

                <!-- Category Name -->
                <div class="flex-1">
                    <label for="name" class="block text-sm font-medium text-slate-300">
                        Category Name <span class="text-rose-500">*</span>
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        placeholder="Restaurant, Travel, Fitness..."
                        class="mt-1.5 block w-full px-3 py-2 border border-slate-850 rounded-xl bg-slate-950/60 text-white">
                </div>

                <!-- Order -->
                <div class="flex-1">
                    <label for="order" class="block text-sm font-medium text-slate-300">
                        Order
                    </label>

                    <input
                        type="number"
                        id="order"
                        name="order"
                        min="0"
                        value="{{ old('order', 0) }}"
                        class="mt-1.5 block w-full px-3 py-2 border border-slate-850 rounded-xl bg-slate-950/60 text-white">
                </div>

            </div>
            <!-- Second Row: Icon + Status -->

            <div class="flex flex-col md:flex-row gap-6">

                <!-- Icon Upload -->
                <div class="flex-1">
                    <label for="icon" class="block text-sm font-medium text-slate-300">
                        Category Icon
                    </label>

                    <div class="mt-1.5 relative">
                        <input
                            type="file"
                            id="icon"
                            name="icon"
                            accept=".png,.jpg,.jpeg,.webp,.svg"
                            class="block w-full px-3 py-2 border border-slate-850 rounded-xl bg-slate-950/60 text-slate-300 text-sm
                   file:mr-3 file:px-3 file:py-1 file:border-0 file:rounded-lg
                   file:bg-slate-800 file:text-slate-200
                   file:[font-family:'Font Awesome 6 Free']">
                    </div>

                    <p class="mt-1 text-xs text-slate-500">

                        Upload PNG, JPG, WEBP or SVG
                    </p>
                </div>

                <!-- Status -->
                <div class="flex-1">
                    <label for="status" class="block text-sm font-medium text-slate-300">
                        Status
                    </label>

                    <select
                        id="status"
                        name="status"
                        class="mt-1.5 block w-full px-3 py-2 border border-slate-850 rounded-xl bg-slate-950/60 text-slate-300">
                        <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="0" {{ old('status', '1') == '0' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>
                </div>

            </div>

            <!-- Submit buttons -->
            <div class="pt-4 border-t border-slate-850 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.offer-categories.index') }}"
                    class="px-4 py-2 border border-slate-800 hover:border-slate-700 text-slate-300 rounded-xl text-sm font-semibold transition">
                    Cancel
                </a>

                <button
                    type="submit"
                    class="px-5 py-2 bg-brand hover:bg-brand-dark text-white rounded-xl text-sm font-semibold shadow-md shadow-brand/20 transition cursor-pointer">
                    Save Category
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
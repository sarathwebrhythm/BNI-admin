@extends('layouts.admin')

@section('title', 'Edit Offer Category')
@section('page_title', 'Edit Offer Category')

@section('content')

<div class="space-y-6">

    <!-- Header navigation -->
    <div>
        <a href="{{ route('admin.offer-categories.index') }}"
            class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-slate-200 transition">
            &larr; Back to Categories
        </a>
    </div>

    <!-- Edit Category Form -->
    <div class="bg-slate-900 border border-slate-800/80 rounded-2xl shadow-lg p-8 max-w-2xl">
        <h3 class="text-lg font-semibold text-slate-200 mb-6 pb-3 border-b border-slate-850">
            Edit Offer Category
        </h3>

        <form action="{{ route('admin.offer-categories.update', $category->id) }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Category Name -->

             <div class="flex gap-6">
               <div class="flex-1">
                <label for="name" class="block text-sm font-medium text-slate-300">
                    Category Name <span class="text-rose-500">*</span>
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $category->name) }}"
                    required
                    class="mt-1.5 appearance-none block w-full px-3 py-2 border border-slate-850 rounded-xl bg-slate-950/60 text-white focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand text-sm transition-all">

                @error('name')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
           <div class="flex-1">
                <label for="order" class="block text-sm font-medium text-slate-300">
                    Display Order
                </label>

                <input
                    type="number"
                    id="order"
                    name="order"
                    min="0"
                    value="{{ old('order', $category->order) }}"
                    class="mt-1.5 block w-full px-3 py-2 border border-slate-850 rounded-xl bg-slate-950/60 text-white">

                @error('order')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            </div>

            <!-- Icon + Status Row -->
            <div class="flex gap-6">

                <!-- Category Icon -->
                <div class="flex-1">
                    <label for="icon" class="block text-sm font-medium text-slate-300">
                        Category Icon
                    </label>

                    <div class="mt-2 flex items-center gap-4">

                        @if($category->icon)
                        <img id="iconPreview"
                            src="{{ asset('storage/' . $category->icon) }}"
                            alt="{{ $category->name }}"
                            class="w-8 h-8 rounded-xl border border-slate-700 bg-white p-2 object-contain">
                        @endif

                        <div class="flex-1">
                            <input
                                type="file"
                                id="icon"
                                name="icon"
                                accept=".png,.jpg,.jpeg,.webp,.svg"
                                onchange="previewIcon(event)"
                                class="block w-full px-3 py-2 border border-slate-850 rounded-xl bg-slate-950/60 text-slate-300 text-sm">
                        </div>

                    </div>

                    <p class="mt-2 text-xs text-slate-500">
                        Upload a new icon to replace the existing one.
                    </p>

                    @error('icon')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="flex-1">
                    <label for="status" class="block text-sm font-medium text-slate-300">
                        Status
                    </label>

                    <select
                        id="status"
                        name="status"
                        class="mt-1.5 block w-full px-3 py-2 border border-slate-850 rounded-xl bg-slate-950/60 text-slate-300 focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand text-sm transition-all cursor-pointer">
                        <option value="1" {{ old('status', $category->status) == 1 ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="0" {{ old('status', $category->status) == 0 ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>

                    @error('status')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <!-- Submit Buttons -->
            <div class="pt-4 border-t border-slate-850 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.offer-categories.index') }}"
                    class="px-4 py-2 border border-slate-800 hover:border-slate-700 text-slate-300 rounded-xl text-sm font-semibold transition">
                    Cancel
                </a>

                <button
                    type="submit"
                    class="px-5 py-2 bg-brand hover:bg-brand-dark text-white rounded-xl text-sm font-semibold shadow-md shadow-brand/20 transition cursor-pointer">
                    Update Category
                </button>
            </div>
        </form>
    </div>


</div>
@endsection
<script>
    function previewIcon(event) {
        const preview = document.getElementById('iconPreview');

        if (event.target.files.length > 0) {
            preview.src = URL.createObjectURL(event.target.files[0]);
        }
    }
</script>
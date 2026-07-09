@extends('layouts.admin')

@section('title', 'Add Member')
@section('page_title', 'Add Member')

@section('content')
<div class="space-y-6">

    <!-- Header navigation -->
    <div>
        <a href="{{ route('admin.members.index') }}" class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-slate-200 transition">
            &larr; Back to Directory
        </a>
    </div>

    <!-- Create Roster Form -->
    <div class="bg-slate-900 border border-slate-800/80 rounded-2xl shadow-lg p-8 max-w-3xl">
        <h3 class="text-lg font-semibold text-slate-200 mb-6 pb-3 border-b border-slate-850">Member Registration Form</h3>

        <form action="{{ route('admin.members.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Form Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-300">Full Name <span class="text-rose-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="mt-1.5 appearance-none block w-full px-3 py-2 border border-slate-850 rounded-xl bg-slate-950/60 text-white focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand text-sm transition-all">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-300">Email Address <span class="text-rose-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        class="mt-1.5 appearance-none block w-full px-3 py-2 border border-slate-850 rounded-xl bg-slate-950/60 text-white focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand text-sm transition-all">
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-300">Phone Number</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                        class="mt-1.5 appearance-none block w-full px-3 py-2 border border-slate-850 rounded-xl bg-slate-950/60 text-white focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand text-sm transition-all">
                </div>
                <!-- Address -->
                <div class="sm:col-span-2">
                    <label for="address" class="block text-sm font-medium text-slate-300">Address</label>
                    <input type="text" id="address" name="address" value="{{ old('address') }}"
                        class="mt-1.5 appearance-none block w-full px-3 py-2 border border-slate-850 rounded-xl bg-slate-950/60 text-white focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand text-sm transition-all">
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-slate-300">Status <span class="text-rose-500">*</span></label>
                    <select id="status" name="status" required
                        class="mt-1.5 block w-full px-3 py-2 border border-slate-850 rounded-xl bg-slate-950/60 text-slate-300 focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand text-sm transition-all cursor-pointer">
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Company -->
                <div>
                    <label for="company" class="block text-sm font-medium text-slate-300">Company Name</label>
                    <input type="text" id="company" name="company" value="{{ old('company') }}"
                        class="mt-1.5 appearance-none block w-full px-3 py-2 border border-slate-850 rounded-xl bg-slate-950/60 text-white focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand text-sm transition-all">
                </div>

                <!-- Chapter -->
                <div>
                    <label for="chapter" class="block text-sm font-medium text-slate-300">BNI Chapter</label>
                    <input type="text" id="chapter" name="chapter" value="{{ old('chapter') }}"
                        class="mt-1.5 appearance-none block w-full px-3 py-2 border border-slate-850 rounded-xl bg-slate-950/60 text-white focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand text-sm transition-all">
                </div>
                <!-- Joining Date -->
                <div>
                    <label for="joining_date" class="block text-sm font-medium text-slate-300">
                        Joining Date
                    </label>
                    <input
                        type="date"
                        id="joining_date"
                        name="joining_date"
                        value="{{ old('joining_date') }}"
                        class="mt-1.5 appearance-none block w-full px-3 py-2 border border-slate-850 rounded-xl bg-slate-950/60 text-white focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand text-sm transition-all">
                </div>

                <!-- Expiry Date -->
                <div>
                    <label for="expire_date" class="block text-sm font-medium text-slate-300">
                        Expiry Date
                    </label>
                    <input
                        type="date"
                        id="expire_date"
                        name="expire_date"
                        value="{{ old('expire_date') }}"
                        class="mt-1.5 appearance-none block w-full px-3 py-2 border border-slate-850 rounded-xl bg-slate-950/60 text-white focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand text-sm transition-all">
                </div>

                <!-- Designation -->
                <div class="sm:col-span-2">
                    <label for="designation" class="block text-sm font-medium text-slate-300">Designation / Classification</label>
                    <input type="text" id="designation" name="designation" value="{{ old('designation') }}"
                        class="mt-1.5 appearance-none block w-full px-3 py-2 border border-slate-850 rounded-xl bg-slate-950/60 text-white focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand text-sm transition-all">
                </div>
            </div>

            <!-- Submit buttons -->
            <div class="pt-4 border-t border-slate-850 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.members.index') }}" class="px-4 py-2 border border-slate-800 hover:border-slate-700 text-slate-300 rounded-xl text-sm font-semibold transition">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2 bg-brand hover:bg-brand-dark text-white rounded-xl text-sm font-semibold shadow-md shadow-brand/20 transition cursor-pointer">
                    Save Member
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
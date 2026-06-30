@extends('layouts.admin')

@section('title', 'View Offer')
@section('page_title', 'View Offer')

@section('content')
<div class="space-y-6">

    <!-- Back -->
    <div>
        <a href="{{ route('admin.offers.index') }}"
            class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-slate-200 transition">
            &larr; Back to Offers
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left: Offer Details -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Main Info -->
            <div class="bg-slate-900 border border-slate-800/80 rounded-2xl shadow-lg p-8">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-850">
                    <h3 class="text-lg font-semibold text-slate-200">Offer Details</h3>
                    @php
                        $statusColors = [
                            'pending'  => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                            'active'   => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                            'inactive' => 'bg-slate-800 text-slate-400 border-slate-700',
                            'rejected' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                        ];
                        $color = $statusColors[$offer->status] ?? 'bg-slate-800 text-slate-400 border-slate-700';
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border {{ $color }}">
                        {{ ucfirst($offer->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500 mb-1">Discount</p>
                        <p class="text-slate-200 font-semibold text-lg">{{ $offer->discount }}</p>
                    </div>

                    @if($offer->title)
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500 mb-1">Title</p>
                        <p class="text-slate-200 font-medium">{{ $offer->title }}</p>
                    </div>
                    @endif

                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500 mb-1">Category</p>
                        <p class="text-slate-300">{{ $offer->category->name ?? '—' }}</p>
                    </div>

                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500 mb-1">Contact Number</p>
                        <p class="text-slate-300">{{ $offer->contact_number ?? '—' }}</p>
                    </div>

                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500 mb-1">Start Date</p>
                        <p class="text-slate-300">{{ $offer->start_date->format('d M Y h:i A') }}</p>
                    </div>

                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500 mb-1">End Date</p>
                        <p class="text-slate-300">{{ $offer->end_date->format('d M Y h:i A') }}</p>
                    </div>

                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500 mb-1">Display Order</p>
                        <p class="text-slate-300">{{ $offer->order }}</p>
                    </div>

                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500 mb-1">Created At</p>
                        <p class="text-slate-300">{{ $offer->created_at->format('d M Y h:i A') }}</p>
                    </div>

                </div>

                @if($offer->description)
                <div class="mt-6">
                    <p class="text-xs uppercase tracking-wide text-slate-500 mb-2">Description</p>
                    <p class="text-slate-300 text-sm leading-relaxed">{{ $offer->description }}</p>
                </div>
                @endif

                @if($offer->terms && count($offer->terms) > 0)
                <div class="mt-6">
                    <p class="text-xs uppercase tracking-wide text-slate-500 mb-2">Terms & Conditions</p>
                    <ul class="space-y-1.5">
                        @foreach($offer->terms as $term)
                        <li class="flex items-start gap-2 text-slate-300 text-sm">
                            <span class="text-brand mt-0.5">•</span>
                            {{ $term }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

            </div>

            <!-- Offer Image -->
            @if($offer->image)
            <div class="bg-slate-900 border border-slate-800/80 rounded-2xl shadow-lg p-6">
                <p class="text-xs uppercase tracking-wide text-slate-500 mb-3">Offer Image</p>
                <img src="{{ asset($offer->image) }}" alt="Offer Image"
                    class="w-full max-h-64 object-cover rounded-xl border border-slate-700">
            </div>
            @endif

        </div>

        <!-- Right: Member Info + Actions -->
        <div class="space-y-6">

            <!-- Member Info -->
            <div class="bg-slate-900 border border-slate-800/80 rounded-2xl shadow-lg p-6">
                <h3 class="text-sm font-semibold text-slate-200 mb-4 pb-3 border-b border-slate-850">Member Info</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-slate-500">Name</p>
                        <p class="text-slate-200 font-medium">{{ $offer->member->name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Company</p>
                        <p class="text-slate-300 text-sm">{{ $offer->member->company ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">BNI ID</p>
                        <p class="text-slate-300 text-sm">{{ $offer->member->bni_id ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Phone</p>
                        <p class="text-slate-300 text-sm">{{ $offer->member->phone ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Chapter</p>
                        <p class="text-slate-300 text-sm">{{ $offer->member->chapter ?? '—' }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-slate-900 border border-slate-800/80 rounded-2xl shadow-lg p-6">
                <h3 class="text-sm font-semibold text-slate-200 mb-4 pb-3 border-b border-slate-850">Actions</h3>
                <div class="space-y-3">

                    @if($offer->status === 'pending')
                    <form action="{{ route('admin.offers.update', $offer->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="active">
                        <button type="submit" class="w-full px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-sm font-semibold transition cursor-pointer">
                            ✓ Approve Offer
                        </button>
                    </form>

                    <form action="{{ route('admin.offers.update', $offer->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="w-full px-4 py-2.5 bg-rose-600 hover:bg-rose-500 text-white rounded-xl text-sm font-semibold transition cursor-pointer">
                            ✗ Reject Offer
                        </button>
                    </form>
                    @endif

                    @if($offer->status === 'active')
                    <form action="{{ route('admin.offers.update', $offer->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="inactive">
                        <button type="submit" class="w-full px-4 py-2.5 bg-amber-600 hover:bg-amber-500 text-white rounded-xl text-sm font-semibold transition cursor-pointer">
                            Deactivate Offer
                        </button>
                    </form>
                    @endif

                    @if($offer->status === 'inactive' || $offer->status === 'rejected')
                    <form action="{{ route('admin.offers.update', $offer->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="active">
                        <button type="submit" class="w-full px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-sm font-semibold transition cursor-pointer">
                            ✓ Activate Offer
                        </button>
                    </form>
                    @endif

                    <form action="{{ route('admin.offers.destroy', $offer->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this offer?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2.5 border border-rose-500/30 hover:bg-rose-500/10 text-rose-400 rounded-xl text-sm font-semibold transition cursor-pointer">
                            Delete Offer
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>

</div>
@endsection
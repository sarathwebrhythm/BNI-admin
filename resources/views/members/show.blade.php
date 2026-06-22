@extends('layouts.admin')

@section('title', 'Member Details')
@section('page_title', 'Member Details')

@section('content')
<div class="space-y-6">

    <!-- Header navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.members.index') }}" class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-slate-200 transition">
            &larr; Back to Directory
        </a>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.members.edit', $member->id) }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold text-slate-900 bg-amber-400 hover:bg-amber-300 rounded-xl transition">
                Edit Member
            </a>
            <form action="{{ route('admin.members.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this member?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 text-xs font-semibold text-white bg-rose-600 hover:bg-rose-500 rounded-xl transition cursor-pointer">
                    Delete Member
                </button>
            </form>
        </div>
    </div>

    <!-- Details Card -->
    <div class="bg-slate-900 border border-slate-800/80 rounded-2xl shadow-lg overflow-hidden grid grid-cols-1 md:grid-cols-3">
        <!-- Left Banner Profile Summary -->
        <div class="p-8 bg-slate-950 border-r border-slate-850 flex flex-col items-center justify-center text-center">
            <div class="h-24 w-24 rounded-2xl bg-brand/15 border border-brand/30 flex items-center justify-center font-bold text-slate-200 text-3xl shadow-lg shadow-brand/5 mb-4">
                {{ substr($member->name, 0, 1) }}
            </div>
            <h3 class="text-xl font-bold text-slate-100">{{ $member->name }}</h3>
            <p class="text-sm text-slate-400 mt-1">{{ $member->designation ?: 'No Designation Specified' }}</p>
            <p class="text-xs text-slate-500 mt-0.5">{{ $member->company ?: 'No Company Specified' }}</p>

            <div class="mt-6">
                @if($member->status === 'active')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    Active Member
                </span>
                @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-slate-800 text-slate-400 border border-slate-700">
                    Inactive Member
                </span>
                @endif
            </div>
        </div>

        <!-- Right Detailed Fields Grid -->
        <div class="md:col-span-2 p-8 space-y-6">
            <h4 class="text-sm font-semibold text-slate-400 uppercase tracking-wider pb-2 border-b border-slate-850">Contact Information</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <span class="block text-xs text-slate-500">Email Address</span>
                    <span class="block text-sm font-medium text-slate-200 mt-1 select-all">{{ $member->email }}</span>
                </div>
                <div>
                    <span class="block text-xs text-slate-500">Phone Number</span>
                    <span class="block text-sm font-medium text-slate-200 mt-1 select-all">{{ $member->phone ?: '—' }}</span>
                </div>
            </div>

            <h4 class="text-sm font-semibold text-slate-400 uppercase tracking-wider pt-4 pb-2 border-b border-slate-850">Chapters & Business Role</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <span class="block text-xs text-slate-500">BNI Chapter</span>
                    <span class="block text-sm font-medium text-slate-200 mt-1">{{ $member->chapter ?: '—' }}</span>
                </div>
                <div>
                    <span class="block text-xs text-slate-500">Designation / Role</span>
                    <span class="block text-sm font-medium text-slate-200 mt-1">{{ $member->designation ?: '—' }}</span>
                </div>
                <div>
                    <span class="block text-xs text-slate-500">Company Name</span>
                    <span class="block text-sm font-medium text-slate-200 mt-1">{{ $member->company ?: '—' }}</span>
                </div>
                <div>
                    <span class="block text-xs text-slate-500">Joining Date</span>
                    <span class="block text-sm font-medium text-slate-200 mt-1">
                        {{ $member->joining_date ? \Carbon\Carbon::parse($member->joining_date)->format('M d, Y') : '—' }}
                    </span>
                </div>
            </div>

            <h4 class="text-sm font-semibold text-slate-400 uppercase tracking-wider pt-4 pb-2 border-b border-slate-850">System Logs</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <span class="block text-xs text-slate-500">Created Date</span>
                    <span class="block text-sm font-medium text-slate-400 mt-1">{{ $member->created_at ? $member->created_at->format('M d, Y \a\t h:i A') : '—' }}</span>
                </div>
                <div>
                    <span class="block text-xs text-slate-500">Last Updated Date</span>
                    <span class="block text-sm font-medium text-slate-400 mt-1">{{ $member->updated_at ? $member->updated_at->format('M d, Y \a\t h:i A') : '—' }}</span>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
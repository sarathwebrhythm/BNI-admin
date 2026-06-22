@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-8">

    <!-- Metrics Cards Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Card: Total Members -->
        <div class="relative overflow-hidden bg-slate-900 border border-slate-800/80 p-6 rounded-2xl shadow-lg group hover:border-slate-700/80 transition-all duration-300">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 rounded-full bg-brand/10 blur-xl group-hover:bg-brand/20 transition-all duration-300"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Members</p>
                    <h3 class="mt-2 text-3xl font-bold text-white tracking-tight">{{ number_format($totalMembers) }}</h3>
                </div>
                <div class="p-3 bg-[#B40E29]/10 text-brand-light rounded-xl">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-slate-500">
                <a href="{{ route('admin.members.index') }}" class="text-brand-light hover:text-brand-light font-medium inline-flex items-center space-x-1 group">
                    <span>Manage all members</span>
                    <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                </a>
            </div>
        </div>

        <!-- Card: Total Uploads -->
        <div class="relative overflow-hidden bg-slate-900 border border-slate-800/80 p-6 rounded-2xl shadow-lg group hover:border-slate-700/80 transition-all duration-300">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 rounded-full bg-emerald-500/10 blur-xl group-hover:bg-emerald-500/20 transition-all duration-300"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Excel Uploads</p>
                    <h3 class="mt-2 text-3xl font-bold text-white tracking-tight">{{ number_format($totalUploads) }}</h3>
                </div>
                <div class="p-3 bg-emerald-500/10 text-emerald-400 rounded-xl">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-slate-500">
                <a href="{{ route('admin.import.show') }}" class="text-emerald-400 hover:text-emerald-300 font-medium inline-flex items-center space-x-1 group">
                    <span>Upload member file</span>
                    <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Data Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Recent Upload History -->
        <div class="lg:col-span-2 bg-slate-900 border border-slate-800/80 rounded-2xl shadow-lg p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-200">Recent Upload History</h2>
                        <p class="text-xs text-slate-400 mt-1">Logs of spreadsheet import processes.</p>
                    </div>
                    <a href="{{ route('admin.import.show') }}" class="text-xs font-semibold text-brand-light hover:text-brand-light bg-slate-800 px-3 py-1.5 rounded-lg border border-slate-700 transition">
                        New Import
                    </a>
                </div>

                @if($recentUploads->isEmpty())
                    <div class="text-center py-12 border border-dashed border-slate-800 rounded-xl bg-slate-950/40">
                        <p class="text-slate-500 text-sm">No uploads recorded yet.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-850">
                            <thead>
                                <tr>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">File Name</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Summary</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Status</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-850 text-sm">
                                @foreach($recentUploads as $upload)
                                    <tr class="hover:bg-slate-800/30 transition-colors">
                                        <td class="px-3 py-3.5 font-medium text-slate-200 max-w-xs truncate">
                                            {{ $upload->filename }}
                                        </td>
                                        <td class="px-3 py-3.5 text-slate-400">
                                            <div class="flex items-center space-x-2 text-xs">
                                                <span class="text-brand-light font-semibold">Total: {{ $upload->total_records }}</span>
                                                <span>•</span>
                                                <span class="text-emerald-400 font-semibold">Imported: {{ $upload->imported_records }}</span>
                                                <span>•</span>
                                                <span class="text-amber-400 font-semibold">Skipped: {{ $upload->skipped_records }}</span>
                                            </div>
                                        </td>
                                        <td class="px-3 py-3.5">
                                            @if($upload->status === 'completed')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                    Completed
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                                    Failed
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-3.5 text-slate-500 text-xs">
                                            {{ $upload->created_at->diffForHumans() }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Activity Feed -->
        <!-- <div class="bg-slate-900 border border-slate-800/80 rounded-2xl shadow-lg p-6" >
            <h2 class="text-lg font-semibold text-slate-200">Recent Activity</h2>
            <p class="text-xs text-slate-400 mt-1 mb-6">Logs of actions executed by admins.</p>

            @if($recentActivity->isEmpty())
                <div class="text-center py-12 border border-dashed border-slate-800 rounded-xl bg-slate-950/40">
                    <p class="text-slate-500 text-sm">No activity recorded yet.</p>
                </div>
            @else
                <div class="flow-root">
                    <ul class="-mb-8">
                        @foreach($recentActivity as $activity)
                            <li>
                                <div class="relative pb-8">
                                    @if (!$loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-800" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-xs text-brand-light shadow-inner">
                                                {{ substr($activity->admin->name ?? 'S', 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-xs text-slate-300 font-medium">
                                                    <span class="text-slate-100 font-semibold">{{ $activity->admin->name ?? 'System' }}</span>
                                                    {{ $activity->activity }}
                                                </p>
                                                <p class="text-[10px] text-slate-500 mt-0.5">IP: {{ $activity->ip_address }}</p>
                                            </div>
                                            <div class="text-right text-[10px] whitespace-nowrap text-slate-500 self-start">
                                                {{ $activity->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div> -->

    </div>

</div>
@endsection
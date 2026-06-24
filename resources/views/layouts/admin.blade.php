<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-900">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BNI Admin') - Admin Dashboard</title>
    <!-- Google Fonts -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>

<body class="h-full text-slate-100 bg-slate-950 flex flex-col md:flex-row">

    <!-- Sidebar -->
    <aside class="bg-slate-900  w-full md:w-64  flex flex-col justify-between shrink-0">
        <div>
            <!-- Sidebar Header -->
            <div class="h-16 flex items-center px-6 border-b border-slate-800">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                    <span class="bg-red-600 text-white p-2 rounded-lg font-bold text-lg tracking-wider shadow-md shadow-indigo-600/30">BNI</span>
                    <span class="font-semibold text-lg text-slate-100 tracking-wide">Admin Portal</span>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="  flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 group {{ Route::is('admin.dashboard') ? 'bg-[#B40E29] text-white shadow-md shadow-indigo-600/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                    <svg class="mr-3 h-5 w-5 {{ Route::is('admin.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-slate-100' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>


                <a href="{{ route('admin.members.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 group {{ Route::is('admin.members.*') ? 'bg-[#B40E29] text-white shadow-md shadow-indigo-600/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                    <svg class="mr-3 h-5 w-5 {{ Route::is('admin.members.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-100' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Member Directory
                </a>

                <a href="{{ route('admin.import.show') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 group {{ Route::is('admin.import.*') ? 'bg-[#B40E29] text-white shadow-md shadow-indigo-600/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                    <svg class="mr-3 h-5 w-5 {{ Route::is('admin.import.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-100' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Import Excel
                </a>
                <a href="{{ route('admin.offer-categories.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 group {{ Route::is('admin.offer-categories.*') ? 'bg-[#B40E29] text-white shadow-md shadow-indigo-600/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                    <svg class="mr-3 h-5 w-5 {{ Route::is('admin.offers.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-100' }}"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 5L5 19M7 7h.01M17 17h.01M9 7a2 2 0 11-4 0 2 2 0 014 0zm10 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Offer Categories
                </a>
                    <a href="{{ route('admin.packages.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 group {{ Route::is('admin.packages.*') ? 'bg-[#B40E29] text-white shadow-md shadow-indigo-600/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                    <svg class="mr-3 h-5 w-5 {{ Route::is('admin.packages.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-100' }}"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 5L5 19M7 7h.01M17 17h.01M9 7a2 2 0 11-4 0 2 2 0 014 0zm10 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                  Packages
                </a>
            </nav>
        </div>

        <!-- Sidebar Footer Admin Session -->
        <div class="p-4 border-t border-slate-800 bg-slate-900 flex flex-col space-y-2">
            <div class="flex items-center space-x-3 px-2">
                <div class="h-9 w-9 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-indigo-400 shadow-inner">
                    {{ substr(Auth::guard('admin')->user()->name ?? 'A', 0, 1) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-medium text-slate-200 truncate">{{ Auth::guard('admin')->user()->name ?? 'Administrator' }}</p>
                    <p class="text-xs text-slate-500 truncate">{{ Auth::guard('admin')->user()->email ?? 'admin@bni.com' }}</p>
                </div>
            </div>

            <form action="{{ route('admin.logout') }}" method="POST" class="w-full pt-1">
                @csrf
                <button type="submit" class="w-full flex items-center px-3 py-2 text-xs font-semibold text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 rounded-lg transition-all duration-200">
                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Sign Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Top bar (only display on md/lg screens) -->
       <header class="h-16 shrink-0 border-b border-slate-800 bg-slate-900/60 backdrop-blur-md sticky top-0 z-10 hidden md:flex items-center justify-between px-8">
            <h1 class="text-lg font-medium text-slate-200">@yield('page_title', 'Dashboard')</h1>
            <div class="text-sm text-slate-400 flex items-center space-x-2">
                <svg class="h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>{{ now()->format('l, F d, Y') }}</span>
            </div>
        </header>

        <!-- Main Body -->
        <main class="p-6 md:p-8 flex-1 overflow-y-auto">
            <!-- Toast notification messages -->
            @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-between shadow-md">
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
                @if(session('summary'))
                <div class="text-xs bg-emerald-500/20 text-emerald-300 px-2 py-1 rounded-lg">
                    Imported: {{ session('summary')['imported'] }} | Skipped: {{ session('summary')['skipped'] }}
                </div>
                @endif
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center space-x-3 shadow-md">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div class="text-sm font-medium">
                    @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>

</html>
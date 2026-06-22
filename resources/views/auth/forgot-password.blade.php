<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - BNI Admin Portal</title>
    <!-- Google Fonts -->
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
<body class="h-full text-slate-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-900 via-slate-950 to-black">

    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <!-- Logo -->
        <div class="flex justify-center">
            <span class="bg-brand text-white px-4 py-3 rounded-2xl font-bold text-2xl tracking-wider shadow-lg shadow-brand/40">BNI</span>
        </div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-white tracking-tight">Forgot Password</h2>
        <p class="mt-2 text-center text-sm text-slate-400">
            Enter your admin email address and we'll send you a password reset link.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-slate-900/40 border border-slate-800/80 p-8 sm:rounded-2xl shadow-xl shadow-black/40 backdrop-blur-md">
            
            <!-- Session Messages -->
            @if(session('success'))
                <div class="mb-4 p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm font-medium flex items-center space-x-2">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-3 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm font-medium">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form class="space-y-6" action="{{ route('admin.forgot-password') }}" method="POST">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-300">Email Address</label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                            class="appearance-none block w-full px-3 py-2.5 border border-slate-800 rounded-xl bg-slate-950/60 placeholder-slate-500 text-white focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand text-sm transition-all">
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-semibold text-white bg-brand hover:bg-brand-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand focus:ring-offset-slate-900 transition-all duration-200 cursor-pointer shadow-brand/20">
                        Send Reset Link
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center text-sm">
                <a href="{{ route('admin.login') }}" class="font-medium text-slate-400 hover:text-white transition-colors">
                    &larr; Back to Login
                </a>
            </div>
        </div>
    </div>

</body>
</html>
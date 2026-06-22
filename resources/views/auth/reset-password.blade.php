<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - BNI Admin Portal</title>
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
        <h2 class="mt-6 text-center text-3xl font-extrabold text-white tracking-tight">Reset Password</h2>
        <p class="mt-2 text-center text-sm text-slate-400">
            Set your new administrator password.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-slate-900/40 border border-slate-800/80 p-8 sm:rounded-2xl shadow-xl shadow-black/40 backdrop-blur-md">
            
            @if($errors->any())
                <div class="mb-4 p-3 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm font-medium">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form class="space-y-6" action="{{ route('admin.reset-password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div>
                    <label class="block text-sm font-medium text-slate-400">Email (Resetting password for)</label>
                    <p class="mt-1 text-sm font-semibold text-slate-200">{{ $email }}</p>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-300">New Password</label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" required autocomplete="new-password"
                            class="appearance-none block w-full px-3 py-2.5 border border-slate-800 rounded-xl bg-slate-950/60 placeholder-slate-500 text-white focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand text-sm transition-all">
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-300">Confirm New Password</label>
                    <div class="mt-1">
                        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                            class="appearance-none block w-full px-3 py-2.5 border border-slate-800 rounded-xl bg-slate-950/60 placeholder-slate-500 text-white focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand text-sm transition-all">
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-semibold text-white bg-brand hover:bg-brand-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand focus:ring-offset-slate-900 transition-all duration-200 cursor-pointer shadow-brand/20">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
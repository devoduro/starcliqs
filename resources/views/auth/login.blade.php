<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Admin sign in — {{ setting('site_name', 'StarCliqs') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script>
        tailwind.config = { theme: { extend: {
            fontFamily: { sans: ['Poppins', 'ui-sans-serif', 'system-ui'] },
            colors: { brand: { 50: '#fdf8ec', 100: '#fbefd0', 300: '#f1c868', 500: '#d99a1e', 600: '#bd7f12', 700: '#9a6311' } },
        } } };
    </script>
</head>
<body class="min-h-screen bg-white font-sans text-gray-900 antialiased">
<div class="flex min-h-screen">
    <div class="flex w-full flex-col px-6 py-8 sm:px-10 lg:w-1/2 xl:px-16">
        <a href="{{ route('home') }}"><img src="{{ asset('images/logo.webp') }}" alt="{{ setting('site_name', 'StarCliqs') }}" class="h-11 w-auto"></a>

        <div class="flex flex-1 items-center justify-center py-10">
            <div class="w-full max-w-sm">
                <h1 class="text-4xl font-extrabold tracking-tight text-gray-900">Welcome back</h1>
                <p class="mt-2 text-gray-500">Sign in to manage the website.</p>

                @if ($errors->any())
                    <div class="mt-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
                        @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                    @csrf
                    <div>
                        <label for="email" class="mb-1.5 block text-sm font-medium text-gray-800">Email address</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                               class="block w-full rounded-md border px-3.5 py-3 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100 {{ $errors->has('email') ? 'border-red-400' : 'border-gray-300' }}">
                    </div>
                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-medium text-gray-800">Password</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" required autocomplete="current-password"
                                   class="block w-full rounded-md border border-gray-300 px-3.5 py-3 pr-11 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100">
                            <button type="button" onclick="const f=document.getElementById('password'); f.type = f.type === 'password' ? 'text' : 'password'; this.firstElementChild.classList.toggle('fa-eye-slash');" aria-label="Show password"
                                    class="absolute inset-y-0 right-0 flex w-11 items-center justify-center text-gray-400 hover:text-gray-600"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="remember" class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500"> Remember me
                    </label>
                    <button type="submit" class="flex w-full items-center justify-center rounded-md bg-gray-900 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-gray-800">
                        Sign in
                    </button>
                </form>
                <p class="mt-8 text-sm text-gray-500"><a href="{{ route('home') }}" class="hover:text-brand-700"><i class="fas fa-arrow-left mr-1"></i> Back to website</a></p>
            </div>
        </div>
    </div>

    <div class="relative hidden overflow-hidden bg-gray-950 lg:flex lg:w-1/2 items-center justify-center p-16">
        <div class="absolute -right-24 -top-24 h-96 w-96 rounded-full bg-brand-500/30 blur-3xl"></div>
        <div class="absolute -bottom-32 -left-20 h-96 w-96 rounded-full bg-brand-500/20 blur-3xl"></div>
        <div class="relative max-w-md text-white">
            <img src="{{ asset('images/favicon.png') }}" alt="" class="h-20 w-20 mb-8">
            <h2 class="text-3xl font-bold leading-tight">Projects, products, trainings and stories — one place to keep them all shining.</h2>
            <p class="mt-4 text-gray-400">Changes you publish here appear on the website instantly.</p>
        </div>
    </div>
</div>
</body>
</html>

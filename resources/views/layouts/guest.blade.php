<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark" data-theme-locked>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-950 font-sans text-fg antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-950">
            <div class="w-full sm:max-w-md mt-8 px-10 py-10 bg-slate-800/50 backdrop-blur-xl shadow-2xl overflow-hidden sm:rounded-3xl border border-slate-600/50">
                <h2 class="text-3xl font-extrabold text-white text-center mb-8 tracking-tight">Welcome Back</h2>
                {{ $slot }}
            </div>
        </div>
    </body>
</html>

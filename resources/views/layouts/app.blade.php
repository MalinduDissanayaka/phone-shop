<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
    <body class="font-sans antialiased">
        <div x-data="{ mobileOpen: false }" class="flex h-screen overflow-hidden bg-gray-100">
            @include('layouts.partials.sidebar')

            <div class="flex min-w-0 flex-1 flex-col">
                <!-- Top Bar -->
                <header class="flex h-16 shrink-0 items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-6">
                    <div class="flex min-w-0 items-center gap-4">
                        <button @click="mobileOpen = !mobileOpen" type="button" class="text-gray-500 hover:text-gray-700 lg:hidden">
                            <x-icon name="menu" class="h-6 w-6" />
                        </button>

                        @isset($header)
                            <div class="min-w-0">{{ $header }}</div>
                        @endisset
                    </div>

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-2 rounded-full py-1 pl-1 pr-2 transition hover:bg-gray-100 focus:outline-none">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 text-sm font-semibold text-white">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                                <span class="hidden text-sm font-medium text-gray-700 sm:block">{{ Auth::user()->name }}</span>
                                <x-icon name="chevron-down" class="hidden h-4 w-4 text-gray-400 sm:block" />
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>

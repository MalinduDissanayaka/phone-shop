@php
    $tiles = [
        ['label' => 'Branches', 'value' => $stats['branches'], 'icon' => 'home'],
        ['label' => 'Users', 'value' => $stats['users'], 'icon' => 'users'],
        ['label' => 'Roles', 'value' => $stats['roles'], 'icon' => 'cog'],
        ['label' => 'Products', 'value' => $stats['products'], 'icon' => 'cube'],
    ];

    $quickLinks = [
        ['label' => 'Manage Branches', 'route' => 'settings.branches.index', 'icon' => 'home'],
        ['label' => 'Manage Roles', 'route' => 'settings.roles.index', 'icon' => 'cog'],
        ['label' => 'Manage Users', 'route' => 'settings.users.index', 'icon' => 'users'],
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm text-fg-muted">Welcome back 👋</p>
            <h2 class="text-xl font-semibold leading-tight text-fg">
                Dashboard
            </h2>
        </div>
    </x-slot>

    <div class="page">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($tiles as $tile)
                <div class="card flex items-center gap-5 p-6">
                    <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-primary to-accent text-white shadow-md shadow-primary/20">
                        <x-icon :name="$tile['icon']" class="h-7 w-7" />
                    </span>
                    <div>
                        <div class="text-sm font-medium text-fg-muted">{{ $tile['label'] }}</div>
                        <div class="mt-1 text-3xl font-bold tabular-nums text-fg">{{ number_format($tile['value']) }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        @if (auth()->user()->isAdmin())
            <div class="card p-6 sm:p-8">
                <h3 class="text-lg font-semibold text-fg">Quick Links</h3>
                <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-3">
                    @foreach ($quickLinks as $link)
                        <a href="{{ route($link['route']) }}" class="group flex items-center gap-4 rounded-xl border border-line p-4 transition hover:border-primary/40 hover:bg-link/5">
                            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-link/10 text-link">
                                <x-icon :name="$link['icon']" class="h-5 w-5" />
                            </span>
                            <span class="text-base font-medium text-fg-soft group-hover:text-fg">{{ $link['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm text-fg-muted">Welcome back 👋</p>
            <h2 class="text-xl font-semibold leading-tight text-fg">
                Dashboard
            </h2>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <div class="card p-5">
                <div class="text-sm font-medium text-fg-muted">Branches</div>
                <div class="mt-2 text-3xl font-semibold text-fg">{{ $stats['branches'] }}</div>
            </div>
            <div class="card p-5">
                <div class="text-sm font-medium text-fg-muted">Users</div>
                <div class="mt-2 text-3xl font-semibold text-fg">{{ $stats['users'] }}</div>
            </div>
            <div class="card p-5">
                <div class="text-sm font-medium text-fg-muted">Roles</div>
                <div class="mt-2 text-3xl font-semibold text-fg">{{ $stats['roles'] }}</div>
            </div>
            <div class="card p-5">
                <div class="text-sm font-medium text-fg-muted">Products</div>
                <div class="mt-2 text-3xl font-semibold text-fg">{{ $stats['products'] }}</div>
            </div>
        </div>

        @if (auth()->user()->isAdmin())
            <div class="mt-8 card p-6">
                <h3 class="text-base font-semibold text-fg">Quick Links</h3>
                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="{{ route('settings.branches.index') }}" class="rounded-lg bg-link/10 px-4 py-2 text-sm font-medium text-link transition hover:bg-link/20">Manage Branches</a>
                    <a href="{{ route('settings.roles.index') }}" class="rounded-lg bg-link/10 px-4 py-2 text-sm font-medium text-link transition hover:bg-link/20">Manage Roles</a>
                    <a href="{{ route('settings.users.index') }}" class="rounded-lg bg-link/10 px-4 py-2 text-sm font-medium text-link transition hover:bg-link/20">Manage Users</a>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>

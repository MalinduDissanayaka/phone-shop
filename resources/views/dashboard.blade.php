<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm text-gray-500">Welcome back 👋</p>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl bg-white p-5 shadow-sm">
                <div class="text-sm font-medium text-gray-500">Branches</div>
                <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $stats['branches'] }}</div>
            </div>
            <div class="rounded-xl bg-white p-5 shadow-sm">
                <div class="text-sm font-medium text-gray-500">Users</div>
                <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $stats['users'] }}</div>
            </div>
            <div class="rounded-xl bg-white p-5 shadow-sm">
                <div class="text-sm font-medium text-gray-500">Roles</div>
                <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $stats['roles'] }}</div>
            </div>
            <div class="rounded-xl bg-white p-5 shadow-sm">
                <div class="text-sm font-medium text-gray-500">Products</div>
                <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $stats['products'] }}</div>
            </div>
        </div>

        @if (auth()->user()->isAdmin())
            <div class="mt-8 rounded-xl bg-white p-6 shadow-sm">
                <h3 class="text-base font-semibold text-gray-800">Quick Links</h3>
                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="{{ route('settings.branches.index') }}" class="rounded-lg bg-indigo-50 px-4 py-2 text-sm font-medium text-indigo-700 hover:bg-indigo-100">Manage Branches</a>
                    <a href="{{ route('settings.roles.index') }}" class="rounded-lg bg-indigo-50 px-4 py-2 text-sm font-medium text-indigo-700 hover:bg-indigo-100">Manage Roles</a>
                    <a href="{{ route('settings.users.index') }}" class="rounded-lg bg-indigo-50 px-4 py-2 text-sm font-medium text-indigo-700 hover:bg-indigo-100">Manage Users</a>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>

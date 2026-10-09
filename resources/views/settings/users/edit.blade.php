<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-fg">
            Edit User
        </h2>
    </x-slot>

    <div class="page-narrow">
        <div class="card p-6 sm:p-8">
            <form method="POST" action="{{ route('settings.users.update', $user) }}" class="space-y-6">
                @csrf
                @method('PUT')
                @include('settings.users._form')

                <x-form-actions :cancel="route('settings.users.index')">Update User</x-form-actions>
            </form>
        </div>
    </div>
</x-app-layout>

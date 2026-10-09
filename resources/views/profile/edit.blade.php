<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-fg leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="page-narrow">
        <div class="card p-6 sm:p-8">
            <div class="max-w-2xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="card p-6 sm:p-8">
            <div class="max-w-2xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="card p-6 sm:p-8">
            <div class="max-w-2xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>

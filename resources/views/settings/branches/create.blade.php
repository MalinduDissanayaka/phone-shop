<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-fg">
            Add Branch
        </h2>
    </x-slot>

    <div class="page-narrow">
        <div class="card p-6 sm:p-8">
            <form method="POST" action="{{ route('settings.branches.store') }}" class="space-y-6">
                @csrf
                @include('settings.branches._form')

                <x-form-actions :cancel="route('settings.branches.index')">Save Branch</x-form-actions>
            </form>
        </div>
    </div>
</x-app-layout>

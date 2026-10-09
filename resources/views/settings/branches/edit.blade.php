<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-fg">
            Edit Branch
        </h2>
    </x-slot>

    <div class="page-narrow">
        <div class="card p-6 sm:p-8">
            <form method="POST" action="{{ route('settings.branches.update', $branch) }}" class="space-y-6">
                @csrf
                @method('PUT')
                @include('settings.branches._form')

                <x-form-actions :cancel="route('settings.branches.index')">Update Branch</x-form-actions>
            </form>
        </div>
    </div>
</x-app-layout>

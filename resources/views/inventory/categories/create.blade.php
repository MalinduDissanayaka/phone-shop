<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-fg">
            Add Category
        </h2>
    </x-slot>

    <div class="page-narrow">
        <div class="card p-6 sm:p-8">
            <form method="POST" action="{{ route('inventory.categories.store') }}" class="space-y-6">
                @csrf
                @include('inventory.categories._form')

                <x-form-actions :cancel="route('inventory.categories.index')">Save Category</x-form-actions>
            </form>
        </div>
    </div>
</x-app-layout>

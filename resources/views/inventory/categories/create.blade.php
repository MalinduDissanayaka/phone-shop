<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Add Category
        </h2>
    </x-slot>

    <div class="mx-auto max-w-2xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('inventory.categories.store') }}" class="space-y-6">
                @csrf
                @include('inventory.categories._form')

                <div class="flex items-center gap-4">
                    <x-primary-button>Save Category</x-primary-button>
                    <a href="{{ route('inventory.categories.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

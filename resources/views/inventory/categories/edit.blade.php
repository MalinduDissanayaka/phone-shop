<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-fg">
            Edit Category
        </h2>
    </x-slot>

    <div class="mx-auto max-w-2xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="card p-6">
            <form method="POST" action="{{ route('inventory.categories.update', $category) }}" class="space-y-6">
                @csrf
                @method('PUT')
                @include('inventory.categories._form')

                <div class="flex items-center gap-4">
                    <x-primary-button>Update Category</x-primary-button>
                    <a href="{{ route('inventory.categories.index') }}" class="text-sm text-fg-muted hover:text-fg">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

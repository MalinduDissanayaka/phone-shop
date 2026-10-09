<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-fg">
            Edit Product
        </h2>
    </x-slot>

    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="card p-6">
            <form method="POST" action="{{ route('inventory.products.update', $product) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                @include('inventory.products._form')

                <div class="flex items-center gap-4">
                    <x-primary-button>Update Product</x-primary-button>
                    <a href="{{ route('inventory.products.create') }}" class="text-sm text-fg-muted hover:text-fg">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

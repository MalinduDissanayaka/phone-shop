<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-fg">
            Edit Product
        </h2>
    </x-slot>

    <div class="page-narrow">
        <div class="card p-6 sm:p-8">
            <form method="POST" action="{{ route('inventory.products.update', $product) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                @include('inventory.products._form')

                <x-form-actions :cancel="route('inventory.products.create')">Update Product</x-form-actions>
            </form>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Add Product
        </h2>
    </x-slot>

    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8 space-y-8">
        @if (session('status'))
            <div class="rounded-lg bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                {{ session('status') }}
            </div>
        @endif

        <div class="rounded-xl bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('inventory.products.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @include('inventory.products._form')

                <div class="flex items-center gap-4">
                    <x-primary-button>Save Product</x-primary-button>
                </div>
            </form>
        </div>

        <div>
            <h3 class="mb-3 text-base font-semibold text-gray-800">Products ({{ $products->count() }})</h3>
            <div class="overflow-hidden rounded-xl bg-white shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Price</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($products as $product)
                            <tr>
                                <td class="whitespace-nowrap px-6 py-3">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}" class="h-10 w-10 rounded-lg border border-gray-100 bg-gray-50 object-contain p-1">
                                        <span class="text-sm font-medium text-gray-900">{{ $product->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-500">
                                    @if ($product->category)
                                        {{ $product->category->parent?->name ? $product->category->parent->name . ' / ' : '' }}{{ $product->category->name }}
                                    @else
                                        <span class="text-gray-400">Uncategorized</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-3 text-sm text-gray-500">Rs {{ number_format($product->price) }}</td>
                                <td class="whitespace-nowrap px-6 py-3 text-right text-sm">
                                    <a href="{{ route('inventory.products.edit', $product) }}" class="font-medium text-indigo-600 hover:text-indigo-500">Edit</a>
                                    <form action="{{ route('inventory.products.destroy', $product) }}" method="POST" class="ml-3 inline" onsubmit="return confirm('Delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-red-600 hover:text-red-500">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">No products yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

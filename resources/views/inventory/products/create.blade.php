<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Add Product
            </h2>
        </div>
    </x-slot>

    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8 space-y-6" x-data="{ viewingProduct: null }">
        @if (session('status'))
            <div class="rounded-lg bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                {{ session('status') }}
            </div>
        @endif

        <div class="flex items-center justify-between">
            <h3 class="text-base font-semibold text-gray-800">Products ({{ $products->count() }})</h3>
            <button type="button" x-on:click="$dispatch('open-modal', 'add-product')"
                class="inline-flex items-center gap-2 rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700">
                <x-icon name="plus" class="h-4 w-4" />
                Add Product
            </button>
        </div>

        <div class="overflow-hidden rounded-xl bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Cost Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Sell Price</th>
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
                            <td class="whitespace-nowrap px-6 py-3 text-sm text-gray-500">
                                {{ $product->cost_price !== null ? 'Rs ' . number_format($product->cost_price) : '—' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-3 text-sm text-gray-500">Rs {{ number_format($product->price) }}</td>
                            <td class="whitespace-nowrap px-6 py-3 text-right text-sm">
                                <button type="button"
                                    x-on:click="viewingProduct = @js([
                                        'name' => $product->name,
                                        'description' => $product->description,
                                        'costPrice' => $product->cost_price !== null ? number_format($product->cost_price) : null,
                                        'price' => number_format($product->price),
                                        'category' => $product->category
                                            ? ($product->category->parent?->name ? $product->category->parent->name . ' / ' : '') . $product->category->name
                                            : null,
                                        'image' => asset('images/' . $product->image),
                                    ]); $dispatch('open-modal', 'view-product')"
                                    class="font-medium text-gray-600 hover:text-gray-900">View</button>
                                <a href="{{ route('inventory.products.edit', $product) }}" class="ml-3 font-medium text-indigo-600 hover:text-indigo-500">Edit</a>
                                <form action="{{ route('inventory.products.destroy', $product) }}" method="POST" class="ml-3 inline" onsubmit="return confirm('Delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 hover:text-red-500">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">No products yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Add Product Modal --}}
        <x-modal name="add-product" max-width="2xl" :show="$errors->any()">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">Add Product</h2>

                <form method="POST" action="{{ route('inventory.products.store') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                    @csrf
                    @include('inventory.products._form', ['product' => null])

                    <div class="flex justify-end gap-3">
                        <x-secondary-button type="button" x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                        <x-primary-button>Save Product</x-primary-button>
                    </div>
                </form>
            </div>
        </x-modal>

        {{-- View Product Modal --}}
        <x-modal name="view-product" max-width="md">
            <template x-if="viewingProduct">
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <img :src="viewingProduct.image" class="h-20 w-20 rounded-lg border border-gray-100 bg-gray-50 object-contain p-1">
                        <div>
                            <h2 class="text-lg font-medium text-gray-900" x-text="viewingProduct.name"></h2>
                            <p class="text-sm text-gray-500" x-text="viewingProduct.category ?? 'Uncategorized'"></p>
                        </div>
                    </div>

                    <p class="mt-4 text-sm text-gray-600" x-text="viewingProduct.description"></p>

                    <div class="mt-4 grid grid-cols-2 gap-4">
                        <div class="rounded-lg bg-gray-50 p-3">
                            <div class="text-xs uppercase tracking-wide text-gray-400">Cost Price</div>
                            <div class="mt-1 font-semibold text-gray-900" x-text="viewingProduct.costPrice ? 'Rs ' + viewingProduct.costPrice : '—'"></div>
                        </div>
                        <div class="rounded-lg bg-gray-50 p-3">
                            <div class="text-xs uppercase tracking-wide text-gray-400">Sell Price</div>
                            <div class="mt-1 font-semibold text-gray-900" x-text="'Rs ' + viewingProduct.price"></div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <x-secondary-button type="button" x-on:click="$dispatch('close')">Close</x-secondary-button>
                    </div>
                </div>
            </template>
        </x-modal>
    </div>
</x-app-layout>

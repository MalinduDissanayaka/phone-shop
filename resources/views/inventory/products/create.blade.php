<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-fg">
                Add Product
            </h2>
        </div>
    </x-slot>

    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8 space-y-6" x-data="{ viewingProduct: null }">
        @if (session('status'))
            <div class="alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div class="flex items-center justify-between">
            <h3 class="text-base font-semibold text-fg">Products ({{ $products->count() }})</h3>
            <button type="button" x-on:click="$dispatch('open-modal', 'add-product')"
                class="btn-primary px-4 py-2 text-xs uppercase tracking-widest">
                <x-icon name="plus" class="h-4 w-4" />
                Add Product
            </button>
        </div>

        <div class="card overflow-hidden">
            <table class="min-w-full divide-y divide-line">
                <thead class="bg-surface-muted">
                    <tr>
                        <th class="table-head">Product</th>
                        <th class="table-head">Category</th>
                        <th class="table-head">Cost Price</th>
                        <th class="table-head">Sell Price</th>
                        <th class="table-head text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse ($products as $product)
                        <tr>
                            <td class="whitespace-nowrap px-6 py-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}" class="h-10 w-10 rounded-lg border border-line bg-surface-muted object-contain p-1">
                                    <span class="text-sm font-medium text-fg">{{ $product->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-sm text-fg-muted">
                                @if ($product->category)
                                    {{ $product->category->parent?->name ? $product->category->parent->name . ' / ' : '' }}{{ $product->category->name }}
                                @else
                                    <span class="text-fg-subtle">Uncategorized</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-3 text-sm text-fg-muted">
                                {{ $product->cost_price !== null ? 'Rs ' . number_format($product->cost_price) : '—' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-3 text-sm text-fg-muted">Rs {{ number_format($product->price) }}</td>
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
                                    class="font-medium text-fg-muted hover:text-fg">View</button>
                                <a href="{{ route('inventory.products.edit', $product) }}" class="ml-3 font-medium text-link hover:text-link/80">Edit</a>
                                <form action="{{ route('inventory.products.destroy', $product) }}" method="POST" class="ml-3 inline" onsubmit="return confirm('Delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-medium text-danger hover:text-danger/80">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-fg-muted">No products yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Add Product Modal --}}
        <x-modal name="add-product" max-width="2xl" :show="$errors->any()">
            <div class="p-6">
                <h2 class="text-lg font-medium text-fg">Add Product</h2>

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
                        <img :src="viewingProduct.image" class="h-20 w-20 rounded-lg border border-line bg-surface-muted object-contain p-1">
                        <div>
                            <h2 class="text-lg font-medium text-fg" x-text="viewingProduct.name"></h2>
                            <p class="text-sm text-fg-muted" x-text="viewingProduct.category ?? 'Uncategorized'"></p>
                        </div>
                    </div>

                    <p class="mt-4 text-sm text-fg-muted" x-text="viewingProduct.description"></p>

                    <div class="mt-4 grid grid-cols-2 gap-4">
                        <div class="rounded-lg bg-surface-muted p-3">
                            <div class="text-xs uppercase tracking-wide text-fg-subtle">Cost Price</div>
                            <div class="mt-1 font-semibold text-fg" x-text="viewingProduct.costPrice ? 'Rs ' + viewingProduct.costPrice : '—'"></div>
                        </div>
                        <div class="rounded-lg bg-surface-muted p-3">
                            <div class="text-xs uppercase tracking-wide text-fg-subtle">Sell Price</div>
                            <div class="mt-1 font-semibold text-fg" x-text="'Rs ' + viewingProduct.price"></div>
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

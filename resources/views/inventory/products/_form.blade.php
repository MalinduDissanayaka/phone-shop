@php
    $product = $product ?? null;
    $initialMainId = '';
    $initialSubId = '';

    if ($product && $product->category) {
        if ($product->category->parent_id) {
            $initialMainId = $product->category->parent_id;
            $initialSubId = $product->category_id;
        } else {
            $initialMainId = $product->category_id;
        }
    }
@endphp

<div
    x-data="{
        mainCategories: @js($mainCategories->map(fn ($c) => [
            'id' => $c->id,
            'name' => $c->name,
            'children' => $c->children->map(fn ($ch) => ['id' => $ch->id, 'name' => $ch->name])->values(),
        ])->values()),
        mainCategoryId: '{{ old('main_category_id', $initialMainId) }}',
        subCategoryId: '{{ old('category_id', $initialSubId) }}',
        get subCategories() {
            const main = this.mainCategories.find(c => c.id == this.mainCategoryId);
            return main ? main.children : [];
        },
    }"
    class="space-y-6"
>
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div>
            <x-input-label for="main_category_id" value="Main Category" />
            <select id="main_category_id" name="main_category_id" x-model="mainCategoryId" @change="subCategoryId = ''"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Select a main category</option>
                @foreach ($mainCategories as $main)
                    <option value="{{ $main->id }}">{{ $main->name }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('main_category_id')" />
        </div>

        <div x-show="subCategories.length > 0">
            <x-input-label for="category_id" value="Sub Category" />
            <select id="category_id" name="category_id" x-model="subCategoryId"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Select a subcategory</option>
                <template x-for="sub in subCategories" :key="sub.id">
                    <option :value="sub.id" :selected="sub.id == subCategoryId" x-text="sub.name"></option>
                </template>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
        </div>
    </div>

    <div>
        <x-input-label for="name" value="Product Name" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $product->name ?? '')" required autofocus placeholder="e.g. iPhone 15 Pro" />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    <div>
        <x-input-label for="description" value="Description" />
        <textarea id="description" name="description" rows="3" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $product->description ?? '') }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('description')" />
    </div>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div>
            <x-input-label for="cost_price" value="Cost Price (Rs)" />
            <x-text-input id="cost_price" name="cost_price" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('cost_price', $product->cost_price ?? '')" required />
            <p class="mt-1 text-xs text-gray-400">What you pay to acquire this product. Not shown to customers.</p>
            <x-input-error class="mt-2" :messages="$errors->get('cost_price')" />
        </div>

        <div>
            <x-input-label for="price" value="Sell Price (Rs)" />
            <x-text-input id="price" name="price" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('price', $product->price ?? '')" required />
            <p class="mt-1 text-xs text-gray-400">The price customers pay in the store.</p>
            <x-input-error class="mt-2" :messages="$errors->get('price')" />
        </div>
    </div>

    <div>
        <x-input-label for="image" :value="$product ? 'Replace Image (optional)' : 'Product Image'" />
        <input id="image" name="image" type="file" accept="image/*" {{ $product ? '' : 'required' }}
            class="mt-1 block w-full text-sm text-gray-600 file:mr-4 file:rounded-md file:border-0 file:bg-gray-800 file:px-4 file:py-2 file:text-xs file:font-semibold file:uppercase file:text-white hover:file:bg-gray-700">
        @if ($product)
            <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}" class="mt-2 h-16 w-16 rounded-lg object-contain bg-gray-50 border border-gray-100 p-1">
        @endif
        <x-input-error class="mt-2" :messages="$errors->get('image')" />
    </div>
</div>

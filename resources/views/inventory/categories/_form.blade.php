@php $category = $category ?? null; @endphp

<div>
    <x-input-label for="name" value="Category Name" />
    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $category->name ?? '')" required autofocus placeholder="e.g. Smartphones, Accessories" />
    <x-input-error class="mt-2" :messages="$errors->get('name')" />
</div>

<div>
    <x-input-label for="parent_id" value="Main Category" />
    <select id="parent_id" name="parent_id"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <option value="">— None (this is a main category) —</option>
        @foreach ($mainCategories as $main)
            <option value="{{ $main->id }}" @selected(old('parent_id', $category->parent_id ?? '') == $main->id)>
                {{ $main->name }}
            </option>
        @endforeach
    </select>
    <p class="mt-1 text-sm text-gray-500">Leave blank to create a main category, or choose one to create a subcategory under it.</p>
    <x-input-error class="mt-2" :messages="$errors->get('parent_id')" />
</div>

@php $branch = $branch ?? null; @endphp

<div>
    <x-input-label for="name" value="Branch Name" />
    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $branch->name ?? '')" required autofocus />
    <x-input-error class="mt-2" :messages="$errors->get('name')" />
</div>

<div>
    <x-input-label for="address" value="Address" />
    <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $branch->address ?? '')" />
    <x-input-error class="mt-2" :messages="$errors->get('address')" />
</div>

<div>
    <x-input-label for="phone" value="Phone Number" />
    <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $branch->phone ?? '')" />
    <x-input-error class="mt-2" :messages="$errors->get('phone')" />
</div>

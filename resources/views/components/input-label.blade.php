@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-fg-soft']) }}>
    {{ $value ?? $slot }}
</label>

@props(['cancel'])

{{-- Footer for create/edit forms: primary submit (slot) plus a Cancel link. --}}
<div {{ $attributes->merge(['class' => 'flex flex-col-reverse gap-3 border-t border-line pt-6 sm:flex-row sm:items-center sm:justify-end']) }}>
    <a href="{{ $cancel }}" class="btn-secondary px-6 py-3 text-sm">Cancel</a>
    <x-primary-button>{{ $slot }}</x-primary-button>
</div>

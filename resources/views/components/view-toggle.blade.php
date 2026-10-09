{{-- Grid / list switch. Must sit inside an element using x-data="viewMode('…')". --}}
<div {{ $attributes->merge(['class' => 'inline-flex rounded-xl border border-line bg-surface p-1 shadow-sm']) }} role="group" aria-label="View mode">
    @foreach (['grid' => ['squares', 'Grid'], 'list' => ['list', 'List']] as $mode => [$icon, $label])
        <button type="button"
            x-on:click="view = '{{ $mode }}'"
            x-bind:aria-pressed="(view === '{{ $mode }}').toString()"
            x-bind:class="view === '{{ $mode }}' ? 'bg-gradient-to-r from-primary to-accent text-white shadow-sm' : 'text-fg-muted hover:text-fg'"
            class="inline-flex items-center gap-2 rounded-lg px-3.5 py-2 text-sm font-medium transition">
            <x-icon :name="$icon" class="h-5 w-5" />
            <span class="hidden sm:inline">{{ $label }}</span>
        </button>
    @endforeach
</div>

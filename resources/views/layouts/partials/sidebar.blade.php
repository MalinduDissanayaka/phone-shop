@php
    $sidebarGroups = config('sidebar');
    $navUser = auth()->user();
@endphp

<div
    x-show="mobileOpen"
    x-transition:enter="transition-opacity ease-linear duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="mobileOpen = false"
    class="fixed inset-0 z-30 bg-gray-900/50 lg:hidden"
    style="display: none;"
></div>

<aside
    :class="mobileOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-40 flex w-64 shrink-0 transform flex-col bg-gray-900 text-gray-300 transition-transform duration-200 ease-in-out lg:static lg:translate-x-0"
>
    <div class="flex h-16 shrink-0 items-center gap-2 border-b border-gray-800 px-5">
        <x-application-logo class="h-8 w-auto fill-current text-white" />
        <span class="truncate text-lg font-semibold text-white">Phone Shop POS</span>
    </div>

    <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
        @foreach ($sidebarGroups as $groupKey => $group)
            @php
                $visibleItems = collect($group['items'])->filter(
                    fn ($item, $key) => $key === 'dashboard' || $navUser->hasPageAccess($key)
                );
                $groupActive = $visibleItems->keys()->contains(
                    fn ($key) => request()->routeIs($visibleItems[$key]['route'])
                );
            @endphp

            @if ($visibleItems->isNotEmpty())
                <div x-data="{ open: @js($groupActive) }">
                    <button
                        @click="open = !open"
                        type="button"
                        class="flex w-full items-center justify-between gap-2 rounded-lg px-3 py-2.5 text-sm font-medium transition hover:bg-gray-800 hover:text-white {{ $groupActive ? 'text-white' : 'text-gray-400' }}"
                    >
                        <span class="flex items-center gap-3">
                            <x-icon :name="$group['icon']" class="h-5 w-5" />
                            {{ $group['label'] }}
                        </span>
                        <x-icon name="chevron-down" class="h-4 w-4 transition-transform" x-bind:class="{ 'rotate-180': open }" />
                    </button>

                    <div x-show="open" class="ml-4 mt-1 space-y-1 border-l border-gray-800 pl-4">
                        @foreach ($visibleItems as $key => $item)
                            <a
                                href="{{ route($item['route']) }}"
                                class="block rounded-lg px-3 py-2 text-sm transition {{ request()->routeIs($item['route']) ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}"
                            >
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </nav>

    <div class="border-t border-gray-800 p-4">
        <div class="text-xs text-gray-500">Logged in as</div>
        <div class="truncate text-sm font-medium text-white">{{ $navUser->name }}</div>
    </div>
</aside>

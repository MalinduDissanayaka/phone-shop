@php
    $role = $role ?? null;
    $selected = old('permissions', $role->permissions ?? []);
@endphp

<div>
    <x-input-label for="name" value="Role Name" />
    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $role->name ?? '')" required autofocus />
    <x-input-error class="mt-2" :messages="$errors->get('name')" />
</div>

<div>
    <x-input-label value="Page Access" />
    <p class="mt-1 text-sm text-fg-muted">Choose which sidebar pages this role can access. Dashboard is always available.</p>

    <div class="mt-3 space-y-5">
        @foreach (config('sidebar') as $group)
            @php
                $groupItems = collect($group['items'])->except('dashboard');
            @endphp

            @if ($groupItems->isNotEmpty())
                <div class="rounded-xl border border-line p-5">
                    <div class="mb-3 flex items-center gap-2 text-base font-semibold text-fg-soft">
                        <x-icon :name="$group['icon']" class="h-5 w-5" />
                        {{ $group['label'] }}
                    </div>
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($groupItems as $key => $item)
                            <label class="flex cursor-pointer items-center gap-3 rounded-lg px-2 py-1.5 text-base text-fg-muted transition hover:bg-surface-muted hover:text-fg">
                                <input type="checkbox" name="permissions[]" value="{{ $key }}"
                                    class="checkbox h-5 w-5"
                                    @checked(in_array($key, $selected))>
                                {{ $item['label'] }}
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    <x-input-error class="mt-2" :messages="$errors->get('permissions')" />
</div>

@php $user = $user ?? null; @endphp

<div>
    <x-input-label for="name" value="Name" />
    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name ?? '')" required autofocus />
    <x-input-error class="mt-2" :messages="$errors->get('name')" />
</div>

<div>
    <x-input-label for="email" value="Email" />
    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email ?? '')" required autocomplete="username" />
    <x-input-error class="mt-2" :messages="$errors->get('email')" />
</div>

<div>
    <x-input-label for="password" :value="$user ? 'New Password (leave blank to keep current)' : 'Password'" />
    <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" {{ $user ? '' : 'required' }} />
    <x-input-error class="mt-2" :messages="$errors->get('password')" />
</div>

<div>
    <x-input-label for="role_id" value="Role" />
    <select id="role_id" name="role_id" required
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <option value="">Select a role</option>
        @foreach ($roles as $role)
            <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id ?? '') == $role->id)>
                {{ $role->name }}{{ $role->is_admin ? ' (Full Access)' : '' }}
            </option>
        @endforeach
    </select>
    <x-input-error class="mt-2" :messages="$errors->get('role_id')" />
</div>

<div>
    <x-input-label for="branch_id" value="Branch" />
    <select id="branch_id" name="branch_id"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <option value="">No branch</option>
        @foreach ($branches as $branch)
            <option value="{{ $branch->id }}" @selected(old('branch_id', $user->branch_id ?? '') == $branch->id)>
                {{ $branch->name }}
            </option>
        @endforeach
    </select>
    <x-input-error class="mt-2" :messages="$errors->get('branch_id')" />
</div>

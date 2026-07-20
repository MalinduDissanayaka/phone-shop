<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::latest()->get();

        return view('settings.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('settings.roles.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        Role::create([
            'name' => $validated['name'],
            'is_admin' => false,
            'permissions' => $validated['permissions'] ?? [],
        ]);

        return redirect()->route('settings.roles.index')->with('status', 'Role created.');
    }

    public function edit(Role $role)
    {
        if ($role->is_admin) {
            return redirect()->route('settings.roles.index')->with('status', 'The Admin role has full access and cannot be edited.');
        }

        return view('settings.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        if ($role->is_admin) {
            abort(403, 'The Admin role cannot be edited.');
        }

        $validated = $this->validated($request);

        $role->update([
            'name' => $validated['name'],
            'permissions' => $validated['permissions'] ?? [],
        ]);

        return redirect()->route('settings.roles.index')->with('status', 'Role updated.');
    }

    public function destroy(Role $role)
    {
        if ($role->is_admin) {
            abort(403, 'The Admin role cannot be deleted.');
        }

        $role->delete();

        return redirect()->route('settings.roles.index')->with('status', 'Role deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string'],
        ]);
    }
}

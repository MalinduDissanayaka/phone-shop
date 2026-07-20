<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::latest()->get();

        return view('settings.branches.index', compact('branches'));
    }

    public function create()
    {
        return view('settings.branches.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        Branch::create($validated);

        return redirect()->route('settings.branches.index')->with('status', 'Branch created.');
    }

    public function edit(Branch $branch)
    {
        return view('settings.branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $this->validated($request);

        $branch->update($validated);

        return redirect()->route('settings.branches.index')->with('status', 'Branch updated.');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();

        return redirect()->route('settings.branches.index')->with('status', 'Branch deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);
    }
}

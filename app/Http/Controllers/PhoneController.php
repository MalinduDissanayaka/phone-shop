<?php

namespace App\Http\Controllers;

use App\Models\Phone;
use Illuminate\Http\Request;

class PhoneController extends Controller
{
    // PUBLIC STORE PAGE
    public function index()
    {
        $phones = Phone::all();
        return view('store', compact('phones'));
    }

    // ADMIN DASHBOARD
    public function adminIndex()
    {
        $phones = Phone::all();
        return view('admin.index', compact('phones'));
    }

    // ADMIN CREATE FORM
    public function create()
    {
        return view('admin.create');
    }

    // SAVE PHONE
    public function store(Request $request)
    {
        $image = $request->file('image');
        $imageName = time() . '.' . $image->extension();
        $image->move(public_path('images'), $imageName);

        Phone::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imageName,
        ]);

        return redirect('/admin/phones');
    }

    // EDIT FORM
    public function edit($id)
    {
        $phone = Phone::findOrFail($id);
        return view('admin.edit', compact('phone'));
    }

    // UPDATE PHONE
    public function update(Request $request, $id)
    {
        $phone = Phone::findOrFail($id);

        $phone->update($request->only('name', 'description', 'price'));

        return redirect('/admin/phones');
    }

    // DELETE PHONE
    public function destroy($id)
    {
        Phone::destroy($id);
        return redirect('/admin/phones');
    }
}

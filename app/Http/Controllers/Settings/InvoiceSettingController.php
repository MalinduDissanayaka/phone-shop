<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\InvoiceSetting;
use Illuminate\Http\Request;

class InvoiceSettingController extends Controller
{
    public function edit()
    {
        $invoiceSetting = InvoiceSetting::current();

        return view('settings.invoice', compact('invoiceSetting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'prefix' => ['required', 'string', 'max:20'],
            'next_number' => ['required', 'integer', 'min:1'],
            'tax_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'currency' => ['required', 'string', 'max:10'],
            'footer_note' => ['nullable', 'string'],
        ]);

        InvoiceSetting::current()->update($validated);

        return redirect()->route('settings.invoice.edit')->with('status', 'Invoice settings updated.');
    }
}

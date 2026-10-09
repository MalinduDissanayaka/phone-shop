<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-fg">
            Invoice Setting
        </h2>
    </x-slot>

    <div class="page-narrow">
        <div class="card p-6 sm:p-8">
            @if (session('status'))
                <div class="mb-6 alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('settings.invoice.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <x-input-label for="prefix" value="Invoice Prefix" />
                        <x-text-input id="prefix" name="prefix" type="text" class="mt-1 block w-full" :value="old('prefix', $invoiceSetting->prefix)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('prefix')" />
                    </div>

                    <div>
                        <x-input-label for="next_number" value="Next Invoice Number" />
                        <x-text-input id="next_number" name="next_number" type="number" min="1" class="mt-1 block w-full" :value="old('next_number', $invoiceSetting->next_number)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('next_number')" />
                    </div>

                    <div>
                        <x-input-label for="tax_rate" value="Tax Rate (%)" />
                        <x-text-input id="tax_rate" name="tax_rate" type="number" step="0.01" min="0" max="100" class="mt-1 block w-full" :value="old('tax_rate', $invoiceSetting->tax_rate)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('tax_rate')" />
                    </div>

                    <div>
                        <x-input-label for="currency" value="Currency" />
                        <x-text-input id="currency" name="currency" type="text" class="mt-1 block w-full" :value="old('currency', $invoiceSetting->currency)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('currency')" />
                    </div>
                </div>

                <div>
                    <x-input-label for="footer_note" value="Invoice Footer Note" />
                    <textarea id="footer_note" name="footer_note" rows="3"
                        class="field mt-1 block w-full">{{ old('footer_note', $invoiceSetting->footer_note) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('footer_note')" />
                </div>

                <div class="flex justify-end border-t border-line pt-6">
                    <x-primary-button>Save Changes</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

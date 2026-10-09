<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-fg">
            My Cart
        </h2>

    </x-slot>

    <div class="page-narrow">

        @if($cartItems->count() > 0)

            @foreach($cartItems as $item)
                <div class="card flex items-center justify-between gap-4 p-6">

                    <div>
                        <h3 class="text-lg font-bold text-fg">{{ $item->phone->name }}</h3>
                        <p class="text-fg-muted">Rs {{ $item->phone->price }}</p>
                        <p class="text-fg-muted">Quantity: {{ $item->quantity }}</p>
                    </div>

                    <form action="{{ route('cart.remove', $item->id) }}"
                          method="POST">
                        @csrf
                        @method('DELETE')

                        <button class="btn-icon w-auto px-4 text-sm font-semibold text-danger hover:bg-danger/10">
                            Remove
                        </button>
                    </form>

                </div>
            @endforeach

        @else
            <div class="rounded-2xl border border-dashed border-line-strong bg-surface px-6 py-20 text-center text-base text-fg-muted">Your cart is empty.</div>
        @endif

    </div>
</x-app-layout>

<?php

use App\Enums\StockMovementType;
use App\Models\Phone;
use App\Models\Role;
use App\Models\User;
use App\Services\StockService;

function stockUser(array $permissions = ['inventory.stock']): User
{
    $role = Role::create(['name' => 'Stock Keeper', 'is_admin' => false, 'permissions' => $permissions]);

    return User::factory()->create(['role_id' => $role->id]);
}

function makeProduct(array $attributes = []): Phone
{
    return Phone::create(array_merge([
        'name' => 'Pixel 9',
        'description' => 'Test phone',
        'price' => 1000,
        'cost_price' => 800,
        'reorder_level' => 5,
        'image' => 'test.png',
    ], $attributes));
}

test('stock page lists products as cards with their quantity', function () {
    $product = makeProduct();
    app(StockService::class)->adjust($product, StockMovementType::In, 12);

    $this->actingAs(stockUser())
        ->get(route('inventory.stock'))
        ->assertOk()
        ->assertSee('Pixel 9')
        ->assertSee('In stock');
});

test('users without page access cannot view or adjust stock', function () {
    $product = makeProduct();
    $user = stockUser([]);

    $this->actingAs($user)->get(route('inventory.stock'))->assertForbidden();
    $this->actingAs($user)->post(route('inventory.stock.adjust', $product), ['type' => 'in', 'quantity' => 3])->assertForbidden();
});

test('stock in, stock out and stock count update the balance and ledger', function () {
    $product = makeProduct();
    $user = stockUser();

    $this->actingAs($user)->post(route('inventory.stock.adjust', $product), ['type' => 'in', 'quantity' => 10, 'note' => 'Delivery'])
        ->assertSessionHasNoErrors();
    $this->actingAs($user)->post(route('inventory.stock.adjust', $product), ['type' => 'out', 'quantity' => 4]);
    $this->actingAs($user)->post(route('inventory.stock.adjust', $product), ['type' => 'adjustment', 'quantity' => 9]);

    expect($product->fresh()->stock_quantity)->toBe(9)
        ->and($product->stockMovements()->oldest('id')->pluck('quantity')->all())->toBe([10, -4, 3])
        ->and($product->stockMovements()->latest('id')->first()->balance_after)->toBe(9)
        ->and($product->stockMovements()->first()->user_id)->toBe($user->id);
});

test('stock cannot go below zero', function () {
    $product = makeProduct();
    app(StockService::class)->adjust($product, StockMovementType::In, 2);

    $this->actingAs(stockUser())
        ->post(route('inventory.stock.adjust', $product), ['type' => 'out', 'quantity' => 5])
        ->assertSessionHasErrorsIn('stockAdjustment', 'quantity');

    expect($product->fresh()->stock_quantity)->toBe(2)
        ->and($product->stockMovements()->count())->toBe(1);
});

test('stock in requires at least one unit and opening type cannot be posted manually', function () {
    $product = makeProduct();
    $user = stockUser();

    $this->actingAs($user)->post(route('inventory.stock.adjust', $product), ['type' => 'in', 'quantity' => 0])
        ->assertSessionHasErrorsIn('stockAdjustment', 'quantity');
    $this->actingAs($user)->post(route('inventory.stock.adjust', $product), ['type' => 'opening', 'quantity' => 5])
        ->assertSessionHasErrorsIn('stockAdjustment', 'type');

    expect($product->fresh()->stock_quantity)->toBe(0);
});

test('status filter returns only matching products', function () {
    $service = app(StockService::class);
    $service->adjust(makeProduct(['name' => 'Healthy Phone']), StockMovementType::In, 50);
    $service->adjust(makeProduct(['name' => 'Low Phone']), StockMovementType::In, 3);
    makeProduct(['name' => 'Empty Phone']);

    $user = stockUser();

    $this->actingAs($user)->get(route('inventory.stock', ['status' => 'low']))
        ->assertSee('Low Phone')->assertDontSee('Healthy Phone')->assertDontSee('Empty Phone');

    $this->actingAs($user)->get(route('inventory.stock', ['status' => 'out']))
        ->assertSee('Empty Phone')->assertDontSee('Low Phone');
});

test('stock movement history is shown for products', function () {
    $product = makeProduct();
    app(StockService::class)->adjust($product, StockMovementType::In, 7, note: 'First delivery');

    $this->actingAs(stockUser())
        ->get(route('inventory.stock'))
        ->assertOk()
        ->assertSee('First delivery');
});

test('creating a product records its opening stock in the ledger', function () {
    $this->actingAs(stockUser(['inventory.add_product']))
        ->post(route('inventory.products.store'), [
            'name' => 'Galaxy S25',
            'description' => 'New arrival',
            'cost_price' => 900,
            'price' => 1200,
            'reorder_level' => 3,
            'opening_stock' => 15,
            'image' => \Illuminate\Http\UploadedFile::fake()->create('phone.png', 10, 'image/png'),
        ])
        ->assertSessionHasNoErrors();

    $product = Phone::where('name', 'Galaxy S25')->firstOrFail();

    expect($product->stock_quantity)->toBe(15)
        ->and($product->reorder_level)->toBe(3)
        ->and($product->stockMovements()->first()->type)->toBe(StockMovementType::Opening);

    @unlink(public_path('images/' . $product->image));
});

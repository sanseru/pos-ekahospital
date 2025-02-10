<?php

use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use App\Models\SaleItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('user can view sales index page with paginated sales', function () {
    // Arrange
    Sale::factory()
        ->count(15)
        ->create([
            'payment_status' => 'paid',
            'invoice_number' => fn() => 'INV-' . Str::random(8)
        ]);

    // Act
    $response = $this->get(route('sales.index'));

    // Assert
    $response->assertStatus(200);
    $response->assertViewIs('sales.index');
    $response->assertViewHas('sales');
    expect(count($response['sales']))->toBe(10); // Check pagination
});

test('user can view create sale page with available products', function () {
    // Arrange
    Product::factory()->count(3)->create(['stock' => 10]);

    // Act
    $response = $this->get(route('sales.create'));

    // Assert
    $response->assertStatus(200);
    $response->assertViewIs('sales.create');
    $response->assertViewHas('products');
});

test('user can create a new sale with multiple items', function () {
    // Arrange
    $products = Product::factory()->count(2)->create([
        'stock' => 20,
        'price' => 100
    ]);

    $saleData = [
        'items' => [
            [
                'product_id' => $products[0]->id,
                'quantity' => 2
            ],
            [
                'product_id' => $products[1]->id,
                'quantity' => 3
            ]
        ],
        'payment_method' => 'cash',
        'customer_name' => 'John Doe'
    ];

    // Act
    $response = $this->post(route('sales.store'), $saleData);

    // Assert
    $response->assertRedirect();
    $this->assertDatabaseHas('sales', [
        'payment_method' => 'cash',
        'payment_status' => 'paid',
        'customer_name' => 'John Doe',
        'total_amount' => 500 // (2 * 100) + (3 * 100)
    ]);

    $sale = Sale::first();
    expect($sale->items)->toHaveCount(2);
    expect($products[0]->fresh()->stock)->toBe(18);
    expect($products[1]->fresh()->stock)->toBe(17);
});

test('sale creation fails with insufficient stock', function () {
    // Arrange
    $product = Product::factory()->create([
        'stock' => 5,
        'name' => 'Test Product'
    ]);

    $saleData = [
        'items' => [
            [
                'product_id' => $product->id,
                'quantity' => 10
            ]
        ],
        'payment_method' => 'cash',
        'customer_name' => 'John Doe'
    ];

    // Act
    $response = $this->post(route('sales.store'), $saleData);

    // Assert
    $response->assertSessionHas('error');
    $this->assertDatabaseCount('sales', 0);
    $this->assertDatabaseCount('sale_items', 0);
    expect($product->fresh()->stock)->toBe(5);
});

test('user can view sale details with items', function () {
    // Arrange
    $products = Product::factory()->count(2)->create();
    $sale = Sale::factory()->create([
        'payment_status' => 'paid',
        'invoice_number' => 'INV-' . Str::random(8)
    ]);

    foreach ($products as $product) {
        SaleItem::create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => $product->price,
            'subtotal' => $product->price * 2
        ]);
    }

    // Act
    $response = $this->get(route('sales.show', $sale));

    // Assert
    $response->assertStatus(200);
    $response->assertViewIs('sales.show');
    $response->assertViewHas('sale');
    expect($sale->items)->toHaveCount(2);
});

test('user can generate invoice pdf', function () {
    // Arrange
    $sale = Sale::factory()->create([
        'invoice_number' => 'INV-' . Str::random(8),
        'payment_status' => 'paid'
    ]);

    // Act
    $response = $this->get(route('sales.invoice', $sale));

    // Assert
    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
});

test('user cannot cancel completed sale', function () {
    // Arrange
    $sale = Sale::factory()->create([
        'payment_status' => 'completed',
        'invoice_number' => 'INV-' . Str::random(8)
    ]);

    // Act
    $response = $this->delete(route('sales.destroy', $sale));

    // Assert
    $response->assertSessionHas('error', 'Error cancelling sale: Cannot delete a completed sale');
    $this->assertDatabaseHas('sales', ['id' => $sale->id]);
});

test('validation fails when creating sale with invalid data', function () {
    // Arrange
    $invalidData = [
        'items' => [],
        'payment_method' => 'invalid',
        'customer_name' => Str::random(300) // Exceeds max length
    ];

    // Act
    $response = $this->post(route('sales.store'), $invalidData);

    // Assert
    $response->assertSessionHasErrors(['items', 'payment_method', 'customer_name']);
    $this->assertDatabaseCount('sales', 0);
});

<?php

use App\Models\Product;
use App\Models\User;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('user can view product index page', function () {
    // Arrange
    Product::factory()->count(5)->create();

    // Act
    $response = $this->get(route('products.index'));

    // Assert
    $response->assertStatus(200);
    $response->assertViewIs('products.index');
    $response->assertViewHas('products');
});

test('user can search products', function () {
    // Arrange
    Product::factory()->create(['name' => 'Test Product']);
    Product::factory()->create(['name' => 'Another Product']);

    // Act
    $response = $this->get(route('products.index', ['search' => 'Test']));

    // Assert
    $response->assertStatus(200);
    $response->assertSee('Test Product');
    $response->assertDontSee('Another Product');
});

test('user can filter products by stock status', function () {
    // Arrange
    $inStock = Product::factory()->create(['stock' => 15]);
    $lowStock = Product::factory()->create(['stock' => 5]);
    $outOfStock = Product::factory()->create(['stock' => 0]);

    // Test in-stock filter
    $response = $this->get(route('products.index', ['filter' => 'in-stock']));
    $response->assertSee($inStock->name);
    $response->assertDontSee($lowStock->name);
    $response->assertDontSee($outOfStock->name);

    // Test low-stock filter
    $response = $this->get(route('products.index', ['filter' => 'low-stock']));
    $response->assertSee($lowStock->name);
    $response->assertDontSee($inStock->name);
    $response->assertDontSee($outOfStock->name);

    // Test out-of-stock filter
    $response = $this->get(route('products.index', ['filter' => 'out-of-stock']));
    $response->assertSee($outOfStock->name);
    $response->assertDontSee($inStock->name);
    $response->assertDontSee($lowStock->name);
});

test('user can create a new product', function () {
    // Arrange
    $productData = [
        'name' => 'New Product',
        'description' => 'Product Description',
        'price' => 100.00,
        'stock' => 10,
        'category' => 'Test Category',
        'type' => 'Barang'
    ];

    // Act
    $response = $this->post(route('products.store'), $productData);

    // Assert
    $response->assertRedirect(route('products.index'));
    $this->assertDatabaseHas('products', [
        'name' => 'New Product',
        'category' => 'Test Category'
    ]);
    $response->assertSessionHas('success', 'Product created successfully');
});

test('user can update an existing product', function () {
    // Arrange
    $product = Product::factory()->create();
    $updatedData = [
        'name' => 'Updated Product',
        'description' => 'Updated Description',
        'price' => 150.00,
        'stock' => 20,
        'category' => 'Updated Category',
        'type' => 'Jasa'
    ];

    // Act
    $response = $this->put(route('products.update', $product), $updatedData);

    // Assert
    $response->assertRedirect(route('products.index'));
    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'name' => 'Updated Product',
        'category' => 'Updated Category'
    ]);
    $response->assertSessionHas('success', 'Product updated successfully');
});

test('user can delete a product', function () {
    // Arrange
    $product = Product::factory()->create();

    // Act
    $response = $this->delete(route('products.destroy', $product));

    // Assert
    $response->assertRedirect(route('products.index'));
    $this->assertDatabaseMissing('products', ['id' => $product->id]);
    $response->assertSessionHas('success', 'Product deleted successfully');
});

test('validation fails when creating product with invalid data', function () {
    // Arrange
    $invalidData = [
        'name' => '', // Required field
        'price' => -100, // Must be positive
        'stock' => -5, // Must be positive
        'category' => '',
        'type' => 'InvalidType' // Must be Barang or Jasa
    ];

    // Act
    $response = $this->post(route('products.store'), $invalidData);

    // Assert
    $response->assertSessionHasErrors(['name', 'price', 'stock', 'category', 'type']);
});

test('error is handled when product creation fails', function () {
    // Arrange
    $this->mock(ProductService::class)
        ->shouldReceive('createProduct')
        ->andThrow(new Exception('Database error'));

    $productData = Product::factory()->make()->toArray();

    // Act
    $response = $this->post(route('products.store'), $productData);

    // Assert
    $response->assertSessionHas('error');
    $response->assertRedirect();
});

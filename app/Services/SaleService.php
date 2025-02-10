<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Str;

class SaleService
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function createSale(array $items, array $saleData)
    {
        $sale = Sale::create([
            'invoice_number' => 'INV-' . Str::random(8),
            'total_amount' => 0,
            'payment_method' => $saleData['payment_method'],
            'payment_status' => 'paid',
            'customer_name' => $saleData['customer_name'] ?? 'Guest'
        ]);

        $totalAmount = 0;

        foreach ($items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $subtotal = $product->price * $item['quantity'];

            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'subtotal' => $subtotal
            ]);

            $this->productService->updateStock($product, $item['quantity']);
            $totalAmount += $subtotal;
        }

        $sale->update(['total_amount' => $totalAmount]);
        return $sale;
    }
}

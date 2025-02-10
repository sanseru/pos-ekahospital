<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function createProduct(array $data)
    {
        return Product::create($data);
    }

    public function updateProduct(Product $product, array $data)
    {
        $product->update($data);
        return $product;
    }

    public function deleteProduct(Product $product)
    {
        return $product->delete();
    }

    public function updateStock(Product $product, int $quantity, string $type = 'decrease')
    {
        if ($type === 'decrease') {
            $product->stock -= $quantity;
        } else {
            $product->stock += $quantity;
        }
        $product->save();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $query = Product::query();

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('category', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Filter functionality
        if ($request->filled('filter') && $request->filter !== 'all') {
            switch ($request->filter) {
                case 'in-stock':
                    $query->where('stock', '>', 10);
                    break;
                case 'low-stock':
                    $query->whereBetween('stock', [1, 10]);
                    break;
                case 'out-of-stock':
                    $query->where('stock', '<=', 0);
                    break;
            }
        }

        $products = $query->orderBy('id', 'desc')->paginate(10)
                         ->withQueryString(); // This preserves the search parameters in pagination links

        return view('products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|string|max:100',
            'type' => 'required|string|in:Barang,Jasa'
        ]);

        $this->productService->createProduct($validated);
        return redirect()->route('products.index')
            ->with('success', 'Product created successfully');
    }

    public function edit(Product $product)
    {
        return response()->json($product);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|string|max:100',
            'type' => 'required|string|in:Barang,Jasa'
        ]);

        $this->productService->updateProduct($product, $validated);
        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $this->productService->deleteProduct($product);
        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully');
    }
}

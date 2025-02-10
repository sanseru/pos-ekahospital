<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Validation\ValidationException;


class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        try {
            $query = Product::query();

            // Search functionality
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
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

            $products = $query->orderBy('id', 'desc')
                ->paginate(10)
                ->withQueryString();

            return view('products.index', compact('products'));
        } catch (Exception $e) {
            Log::error('Error fetching products: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while fetching products. Please try again.');
        }
    }

    public function store(Request $request)
    {
        try {
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
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Please check the form for errors');
        } catch (Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while creating the product. Please try again.');
        }
    }

    public function edit(Product $product)
    {
        try {
            return response()->json($product);
        } catch (Exception $e) {
            Log::error('Error fetching product details: ' . $e->getMessage());
            return response()->json([
                'error' => 'An error occurred while fetching product details.'
            ], 500);
        }
    }

    public function update(Request $request, Product $product)
    {
        try {
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
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Please check the form for errors');
        } catch (Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while updating the product. Please try again.');
        }
    }

    public function destroy(Product $product)
    {
        try {
            $this->productService->deleteProduct($product);

            return redirect()->route('products.index')
                ->with('success', 'Product deleted successfully');
        } catch (Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while deleting the product. Please try again.');
        }
    }
}

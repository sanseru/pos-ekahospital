<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Services\SaleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    protected $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    public function index()
    {
        try {
            $sales = Sale::with('items.product')
                ->latest()
                ->paginate(10);

            return view('sales.index', compact('sales'));
        } catch (Exception $e) {
            Log::error('Error fetching sales: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while fetching sales. Please try again.');
        }
    }

    public function create()
    {
        try {
            $products = Product::where('stock', '>', 0)
                ->orderBy('name')
                ->get(['id', 'name', 'price', 'stock']);

            return view('sales.create', compact('products'));
        } catch (Exception $e) {
            Log::error('Error loading create sale page: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while loading the create sale page. Please try again.');
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'payment_method' => 'required|in:cash,card,transfer',
                'customer_name' => 'nullable|string|max:255'
            ]);

            DB::beginTransaction();

            // Check stock availability
            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) {
                    throw new Exception("Product not found");
                }
                if ($product->stock < $item['quantity']) {
                    throw new Exception("Insufficient stock for product: {$product->name}");
                }
            }

            $sale = $this->saleService->createSale($validated['items'], $validated);

            DB::commit();

            return redirect()
                ->route('sales.show', $sale)
                ->with('success', 'Sale completed successfully');
        } catch (ValidationException $e) {
            DB::rollBack();
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Please check the form for errors');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating sale: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Error creating sale: ' . $e->getMessage());
        }
    }

    public function show(Sale $sale)
    {
        try {
            $sale->load(['items.product']);
            return view('sales.show', compact('sale'));
        } catch (Exception $e) {
            Log::error('Error showing sale details: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while loading sale details. Please try again.');
        }
    }

    public function generateInvoice(Sale $sale)
    {
        try {
            $sale->load(['items.product']);

            $pdf = PDF::loadView('sales.invoice', [
                'sale' => $sale,
                'company' => [
                    'name' => config('app.name'),
                    'address' => config('app.address'),
                    'phone' => config('app.phone'),
                    'email' => config('app.email'),
                ]
            ]);

            return $pdf->download("invoice-{$sale->invoice_number}.pdf");
        } catch (Exception $e) {
            Log::error('Error generating invoice: ' . $e->getMessage());
            return back()
                ->with('error', 'Error generating invoice: ' . $e->getMessage());
        }
    }

    public function destroy(Sale $sale)
    {
        try {
            if ($sale->payment_status === 'completed') {
                throw new Exception('Cannot delete a completed sale');
            }

            DB::beginTransaction();

            // Restore product stock
            foreach ($sale->items as $item) {
                $product = $item->product;
                if (!$product) {
                    throw new Exception('Product not found for sale item');
                }
                $product->increment('stock', $item->quantity);
            }

            $sale->delete();

            DB::commit();

            return redirect()
                ->route('sales.index')
                ->with('success', 'Sale cancelled successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error cancelling sale: ' . $e->getMessage());
            return back()
                ->with('error', 'Error cancelling sale: ' . $e->getMessage());
        }
    }
}

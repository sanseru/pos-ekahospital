<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Services\SaleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleController extends Controller
{
    protected $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    public function index()
    {
        $sales = Sale::with('items.product')
            ->latest()
            ->paginate(10);

        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::where('stock', '>', 0)
            ->orderBy('name')
            ->get(['id', 'name', 'price', 'stock']);

        return view('sales.create', compact('products'));
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
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }
            }

            $sale = $this->saleService->createSale($validated['items'], $validated);

            DB::commit();

            return redirect()
                ->route('sales.show', $sale)
                ->with('success', 'Sale completed successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Error creating sale: ' . $e->getMessage());
        }
    }

    public function show(Sale $sale)
    {
        $sale->load(['items.product']);
        return view('sales.show', compact('sale'));
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

        } catch (\Exception $e) {
            return back()->with('error', 'Error generating invoice: ' . $e->getMessage());
        }
    }

    public function destroy(Sale $sale)
    {
        try {
            if ($sale->payment_status === 'completed') {
                throw new \Exception('Cannot delete a completed sale');
            }

            DB::beginTransaction();

            // Restore product stock
            foreach ($sale->items as $item) {
                $product = $item->product;
                $product->increment('stock', $item->quantity);
            }

            $sale->delete();

            DB::commit();

            return redirect()
                ->route('sales.index')
                ->with('success', 'Sale cancelled successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error cancelling sale: ' . $e->getMessage());
        }
    }
}

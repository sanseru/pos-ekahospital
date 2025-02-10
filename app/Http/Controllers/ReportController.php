<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());

        $sales = Sale::whereBetween('created_at', [$startDate, $endDate])
                     ->with(['items.product'])
                     ->get();

        $totalSales = $sales->sum('total_amount');
        $totalTransactions = $sales->count();

        $dailySales = $sales->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('Y-m-d');
        });

        return view('reports.index', compact('sales', 'totalSales', 'totalTransactions', 'dailySales', 'startDate', 'endDate'));
    }

    public function export(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $sales = Sale::whereBetween('created_at', [$startDate, $endDate])
                     ->with(['items.product'])
                     ->get();

        $pdf = PDF::loadView('reports.export', compact('sales', 'startDate', 'endDate'));
        return $pdf->download('sales-report.pdf');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\PurchaseInvoice;
use Carbon\Carbon;


class ReportsController extends Controller
{
    // ===========================
    // SALES REPORT
    // ===========================
    public function saleReport(Request $request)
    {
        $from = $request->from_date ?? now()->startOfMonth()->toDateString();
        $to   = $request->to_date ?? now()->endOfMonth()->toDateString();

        $sales = Invoice::whereBetween('invoice_date', [$from, $to])->get();

        return view('reports.reports', [
            'from_date' => $from,
            'to_date'   => $to,
            'subtotal'  => $sales->sum('subtotal'),
            'total_discount' => $sales->sum('discount'),
            'total_sold' => $sales->sum('total'),
            'customer_paid' => $sales->sum('paidamount'),
            'customer_due'  => $sales->sum('remainingamount'),
        ]);
    }

    // ===========================
    // PURCHASE REPORT
    // ===========================
    public function purchaseReport(Request $request)
    {
        $from = $request->from_date ?? now()->startOfMonth()->toDateString();
        $to   = $request->to_date ?? now()->endOfMonth()->toDateString();

        $purchases = PurchaseInvoice::whereBetween('invoice_date', [$from, $to])->get();

        return view('reports.reports', [
            'from_date' => $from,
            'to_date'   => $to,
            'total_purchase' => $purchases->sum('total'),
        ]);
    }

    // ===========================
    // PROFIT / LOSS REPORT
    // ===========================
public function profitLoss(Request $request)
{
    // Read filter dates
    $from = $request->from_date;
    $to   = $request->to_date;

    // If no dates entered → default to current month
    if (!$from || !$to) {
        $from = Carbon::now()->startOfMonth()->toDateString();
        $to   = Carbon::now()->endOfMonth()->toDateString();
    }

    // -------- Total Sales (from Invoice model) --------
    $total_sales = Invoice::whereBetween('invoice_date', [$from, $to])
                          ->sum('total');  // invoice total

    // -------- Total Purchases (from PurchaseInvoice model) --------
    $total_purchase = PurchaseInvoice::whereBetween('invoice_date', [$from, $to])
                                     ->sum('grand_total'); // correct column

    // -------- Profit / Loss --------
    $profit = floatval($total_sales) - floatval($total_purchase);

    return view('reports.profit-loss', [
        'from_date'      => $from,
        'to_date'        => $to,
        'total_sales'    => $total_sales,
        'total_purchase' => $total_purchase,
        'profit'         => $profit,
    ]);
}


}

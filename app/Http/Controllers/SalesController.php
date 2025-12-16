<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index()
    {
        $sales = Invoice::with('client')
            ->withCount('items')
            ->orderBy('id', 'ASC')
            ->get();

        return view('sales', compact('sales'));
    }

    // 1) First icon → Print Invoice (returns a blade view)
    public function printInvoice($id)
    {
        $invoice = Invoice::with(['client', 'items.product'])->findOrFail($id);
        return view('invoices.print', compact('invoice'));
    }

    // 2) Second icon → POS Bill Generate (returns JSON)
public function posInvoice($id)
{
    $invoice = Invoice::with(['client','items.product'])->findOrFail($id);

    // Build items with rate, qty, before_amount, discount, final_amount
    $items = $invoice->items->map(function($item) {

        $rate = (float)$item->price;   // item rate (column: price)
        $qty  = (float)$item->quantity;

        $before = $rate * $qty;        // rate * qty

        $discountPercent = (float)($item->discount ?? 0);  // discount % per item
        $discountAmount = round(($before * $discountPercent) / 100, 2);

        $final = $before - $discountAmount;

        return [
            'product_name'    => $item->product->name,
            'hsn_code'        => $item->product->hsn_code,
            'rate'            => $rate,
            'quantity'        => $qty,
            'before_amount'   => round($before, 2),
            'discount'        => $discountPercent,
            'discount_amount' => $discountAmount,
            'total'           => round($final, 2)
        ];
    });

    // Totals
    $subtotal = $items->sum('before_amount');
    $total_item_discount = $items->sum('discount_amount');

    $taxRate = (float)($invoice->tax ?? 0);
    $taxAmount = round(($subtotal - $total_item_discount) * $taxRate / 100, 2);

    $grandTotal = round(($subtotal - $total_item_discount) + $taxAmount, 2);

    return response()->json([
        'id' => $invoice->id,
        'invoice_no' => $invoice->invoice_number,
        'client_name' => $invoice->client->name,
        'date' => $invoice->created_at->format('d-m-Y'),

        'items' => $items,

        // Totals
        'subtotal' => $subtotal,
        'total_item_discount' => $total_item_discount,
        'tax_rate' => $taxRate,
        'tax_amount' => $taxAmount,
        'total' => $grandTotal,

        // Extra qty total
        'total_qty' => $items->sum('quantity')
    ]);
}

    // 3) Third icon → Delete Invoice
    public function destroy($id)
    {
        Invoice::findOrFail($id)->delete();
        return redirect()->back()->with('success','Invoice deleted successfully');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceItem;
use App\Models\Vendor;
use App\Models\Product;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PurchaseInvoiceController extends Controller
{
    // List all invoices
    public function index()
    {
        $invoices = PurchaseInvoice::with('supplier')->get();
        return view('purchases.purchase', compact('invoices'));
    }

    // Show create form
    public function create()
    {
        $suppliers = Vendor::all();
        $products  = Product::all();

        return view('purchases.create', compact('suppliers', 'products'));
    }

    // Show a single invoice (View Page)
    public function show($id)
    {
        $invoice = PurchaseInvoice::with('items.product', 'supplier')->findOrFail($id);
        return view('purchases.show', compact('invoice'));
    }

    // EDIT Invoice Page
    public function edit($id)
    {
        $invoice   = PurchaseInvoice::with('items.product')->findOrFail($id);
        $suppliers = Vendor::all();
        $products  = Product::all();

        return view('purchases.edit', compact('invoice', 'suppliers', 'products'));
    }

    // UPDATE Invoice
    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier_id'   => 'required',
            'invoice_date'  => 'required|date',
            'product_id'    => 'required|array',
            'qty'           => 'required|array',
            'price'         => 'required|array',
            'tax_percent'   => 'required|array',
            'discount'      => 'nullable|numeric|min:0',
        ]);

        $invoice = PurchaseInvoice::findOrFail($id);

        $productIds   = $request->product_id;
        $quantities   = $request->qty;
        $prices       = $request->price;
        $taxPercents  = $request->tax_percent;

        $subtotal = 0;
        $totalTax = 0;

        // First calculate totals
        foreach ($productIds as $i => $productValue) {
            $qty        = floatval($quantities[$i]);
            $price      = floatval($prices[$i]);
            $taxPercent = floatval($taxPercents[$i]);

            $itemTotal  = $qty * $price;
            $taxAmount  = ($itemTotal * $taxPercent) / 100;

            $subtotal += $itemTotal;
            $totalTax += $taxAmount;
        }

        $discount   = floatval($request->discount ?? 0);
        $grandTotal = $subtotal + $totalTax - $discount;

        // Update invoice
        $invoice->update([
            'supplier_id'  => $request->supplier_id,
            'invoice_date' => $request->invoice_date,
            'subtotal'     => $subtotal,
            'tax'          => $totalTax,
            'discount'     => $discount,
            'grand_total'  => $grandTotal,
        ]);

        // Remove old items
        $invoice->items()->delete();

        // Insert updated items
        foreach ($productIds as $i => $productValue) {
            // If the product value is numeric, it’s an existing product
            if (is_numeric($productValue)) {
                $productId = $productValue;
            } else {
                // Otherwise, create a new product
                $newProduct = Product::create([
                    'name'  => $productValue,
                    'price' => floatval($prices[$i]),
                    // You can set default values for other fields here if needed
                ]);
                $productId = $newProduct->id;
            }

            $qty        = floatval($quantities[$i]);
            $price      = floatval($prices[$i]);
            $taxPercent = floatval($taxPercents[$i]);

            $itemTotal = $qty * $price;
            $taxAmount = ($itemTotal * $taxPercent) / 100;
            $finalRow  = $itemTotal + $taxAmount;

            PurchaseInvoiceItem::create([
                'purchase_invoice_id' => $invoice->id,
                'product_id'          => $productId,
                'qty'                 => $qty,
                'price'               => $price,
                'tax_percent'         => $taxPercent,
                'item_total'          => $finalRow,
                'total'               => $finalRow,
            ]);
        }

        return redirect()
            ->route('purchase.invoice.index')
            ->with('success', 'Invoice Updated Successfully');
    }

    // Store invoice
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'   => 'required',
            'invoice_date'  => 'required|date',
            'product_id'    => 'required|array',
            'qty'           => 'required|array',
            'price'         => 'required|array',
            'tax_percent'   => 'required|array',
            'discount'      => 'nullable|numeric|min:0',
        ]);

        $productIds   = $request->product_id ?? [];
        $quantities   = $request->qty ?? [];
        $prices       = $request->price ?? [];
        $taxPercents  = $request->tax_percent ?? [];

        $subtotal = 0;
        $totalTax = 0;

        // First calculate totals
        foreach ($productIds as $i => $productValue) {
            if (!$productValue) continue;

            $qty        = floatval($quantities[$i] ?? 0);
            $price      = floatval($prices[$i] ?? 0);
            $taxPercent = floatval($taxPercents[$i] ?? 0);

            $itemTotal = $qty * $price;
            $taxAmount = ($itemTotal * $taxPercent) / 100;

            $subtotal += $itemTotal;
            $totalTax += $taxAmount;
        }

        $discount   = floatval($request->discount ?? 0);
        $grandTotal = $subtotal + $totalTax - $discount;

        $invoice = PurchaseInvoice::create([
            'supplier_id'  => $request->supplier_id,
            'invoice_date' => $request->invoice_date,
            'subtotal'     => $subtotal,
            'tax'          => $totalTax,
            'discount'     => $discount,
            'grand_total'  => $grandTotal,
            'invoice_no'   => 'INV-' . time(),
        ]);

        // Insert items
        foreach ($productIds as $i => $productValue) {
            if (!$productValue) continue;

            if (is_numeric($productValue)) {
                $productId = $productValue;
            } else {
                $newProduct = Product::create([
                    'name'  => $productValue,
                    'price' => floatval($prices[$i] ?? 0),
                ]);
                $productId = $newProduct->id;
            }

            $qty        = floatval($quantities[$i] ?? 0);
            $price      = floatval($prices[$i] ?? 0);
            $taxPercent = floatval($taxPercents[$i] ?? 0);

            $itemTotal     = $qty * $price;
            $taxAmount     = ($itemTotal * $taxPercent) / 100;
            $finalRowTotal = $itemTotal + $taxAmount;

            PurchaseInvoiceItem::create([
                'purchase_invoice_id' => $invoice->id,
                'product_id'          => $productId,
                'qty'                 => $qty,
                'price'               => $price,
                'tax_percent'         => $taxPercent,
                'item_total'          => $finalRowTotal,
                'total'               => $finalRowTotal,
            ]);
        }

        return redirect()->route('purchase.invoice.index')
            ->with('success', 'Invoice created successfully!');
    }

    // Delete invoice
    public function destroy($id)
    {
        $invoice = PurchaseInvoice::findOrFail($id);
        $invoice->items()->delete();
        $invoice->delete();

        return back()->with('success', 'Invoice Deleted Successfully');
    }

    // Download PDF
    public function downloadPDF($id)
    {
        $invoice = PurchaseInvoice::with('items.product', 'supplier')->findOrFail($id);
        $pdf = Pdf::loadView('purchases.pdf', compact('invoice'));
        return $pdf->download('invoice_' . $invoice->invoice_no . '.pdf');
    }

    // ⭐ FIX: Add missing download() method
    public function download($id)
    {
        return $this->downloadPDF($id);
    }
}

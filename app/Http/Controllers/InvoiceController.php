<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /** ----------------------------------------------------
     *  Show all invoices (History Page)
     *  ---------------------------------------------------- */
    public function index()
    {
        // Show invoices oldest to newest (so new one appears last)
        $invoices = Invoice::with('client')->orderBy('created_at', 'asc')->get();
        return view('invoice', compact('invoices')); // resources/views/invoice.blade.php
    }

    /** ----------------------------------------------------
     *  Show Create Invoice Form
     *  ---------------------------------------------------- */
    public function create()
    {
        $clients = Client::all();
        $products = Product::all();
        return view('createinvoice', compact('clients', 'products')); // resources/views/createinvoice.blade.php
    }

    /** ----------------------------------------------------
     *  Edit Invoice
     *  ---------------------------------------------------- */
    public function edit($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        $clients = Client::all();
        $products = Product::all();

        return view('editinvoice', compact('invoice', 'clients', 'products'));
    }

    /** ----------------------------------------------------
     *  Store New Invoice (Auto Increment Invoice Number)
     *  ---------------------------------------------------- */
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'status' => 'required|string|max:50',
            'tax' => 'required|numeric',
            'invoice_date' => 'required|date',
            'paid_amount' => 'nullable|numeric|min:0',
            'remaining_amount' => 'nullable|numeric|min:0',
        ]);

        // 🔹 Find the latest invoice number
        $lastInvoice = Invoice::orderBy('id', 'desc')->first();

        // 🔹 Get next invoice number
        $nextNumber = 1;
        if ($lastInvoice && !empty($lastInvoice->invoice_number)) {
            $lastNumber = (int) str_replace('INV-', '', $lastInvoice->invoice_number);
            $nextNumber = $lastNumber + 1;
        }

        // 🔹 Format the new invoice number
        $invoiceNumber = 'INV-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // 🔹 Create invoice record
        $invoice = new Invoice();
        $invoice->invoice_number = $invoiceNumber;
        $invoice->client_id = $request->client_id;
        $invoice->status = $request->status;
        $invoice->tax = $request->tax;
        $invoice->invoice_date = $request->invoice_date;
        $invoice->subtotal = 0;
        $invoice->total = 0;
        $invoice->paidamount = $request->paid_amount ?? 0;
        $invoice->remainingamount = $request->remaining_amount ?? 0;
        $invoice->save();

        // 🔹 Add invoice items
       $subtotal = 0;

foreach ($request->product_id as $index => $productId) {
    if (!$productId) continue;

    $qty = (float) $request->quantity[$index];
    $price = (float) $request->price[$index];
    $discount = (float) $request->discount[$index];   // ⭐ GET DISCOUNT CORRECTLY

    $lineTotal = $qty * $price;
    $discountAmount = ($lineTotal * $discount) / 100; // ⭐ CALCULATE DISCOUNT
    $finalTotal = $lineTotal - $discountAmount;

    $subtotal += $finalTotal;

    InvoiceItem::create([
        'invoice_id' => $invoice->id,
        'product_id' => $productId,
        'quantity'   => $qty,
        'price'      => $price,
        'discount'   => $discount,      // ⭐ INSERT CORRECT VALUE
        'total'      => $finalTotal     // ⭐ INSERT AFTER DISCOUNT
    ]);

    // Update Stock
    $product = Product::find($productId);
    if ($product) {
        $product->stock = max(0, $product->stock - $qty);
        $product->save();
    }
}


        // 🔹 Calculate totals
        $gst = $subtotal * ($request->tax / 100);
        $grandTotal = $subtotal + $gst;

        // 🔹 Set payment amounts based on status
        $paidAmount = 0;
        $remainingAmount = 0;

        if ($request->status === 'Paid') {
            $paidAmount = $grandTotal;
            $remainingAmount = 0;
        } elseif ($request->status === 'Partially Paid') {
            $paidAmount = (float) $request->paid_amount;
            $remainingAmount = (float) $request->remaining_amount;
        } else {
            $paidAmount = 0;
            $remainingAmount = $grandTotal;
        }

        // 🔹 Update invoice totals
        $invoice->update([
            'subtotal' => $subtotal,
            'total' => $grandTotal,
            'paidamount' => $paidAmount,
            'remainingamount' => $remainingAmount,
        ]);
        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully!');
    }

    /** ----------------------------------------------------
     *  Update Invoice
     *  ---------------------------------------------------- */
    public function update(Request $request, $id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'status' => 'required|string',
            'product_id' => 'required|array',
            'product_id.*' => 'exists:products,id',
            'quantity' => 'required|array',
            'quantity.*' => 'numeric|min:0',
            'price' => 'required|array',
            'price.*' => 'numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
        ]);

        $subtotal = 0;
        foreach ($validated['product_id'] as $key => $pid) {
            $subtotal += $validated['quantity'][$key] * $validated['price'][$key];
        }

        $tax = $validated['tax'] ?? 0;
        $grandTotal = $subtotal + ($subtotal * $tax / 100);

        // Update main invoice
        $invoice->update([
            'client_id' => $validated['client_id'],
            'status' => $validated['status'],
            'tax' => $tax,
            'total' => $grandTotal,
        ]);

        // Remove old items
        InvoiceItem::where('invoice_id', $invoice->id)->delete();

        // Add updated items
        foreach ($validated['product_id'] as $index => $pid) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $pid,
                'quantity' => $validated['quantity'][$index],
                'price' => $validated['price'][$index],
                'total' => $validated['quantity'][$index] * $validated['price'][$index],
            ]);
        }

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully!');
    }

    /** ----------------------------------------------------
     *  Delete Invoice
     *  ---------------------------------------------------- */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }
}

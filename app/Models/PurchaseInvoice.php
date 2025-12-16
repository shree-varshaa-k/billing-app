<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
    protected $fillable = [
        'invoice_no',
        'invoice_number',
        'supplier_id',
        'invoice_date',
        'subtotal',
        'tax',
        'discount',
        'grand_total'
    ];

    public function supplier()
    {
        return $this->belongsTo(Vendor::class, 'supplier_id');
    }

    public function items()
    {
        return $this->hasMany(PurchaseInvoiceItem::class, 'purchase_invoice_id');
    }

    // Helper method to add item
    public function addItem($productId, $qty, $price)
    {
        $total = $qty * $price;
        return $this->items()->create([
            'product_id' => $productId,
            'qty' => $qty,
            'price' => $price,
            'total' => $total
        ]);
    }

    // Calculate grand total automatically
    public function calculateGrandTotal()
    {
        $this->subtotal = $this->items()->sum('total');
        $this->grand_total = $this->subtotal + $this->tax - $this->discount;
        $this->save();
    }
}

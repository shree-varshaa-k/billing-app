<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
     protected $table = 'invoices';  //  sales table

    protected $fillable = [
    'invoice_number', 'client_id', 'invoice_date', 'subtotal', 'tax', 'total', 'status',
    'paidamount', 'remainingamount'
];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

   public function items()
{
    return $this->hasMany(InvoiceItem::class, 'invoice_id');
}

    // ✅ Handle stock restoration automatically when invoice is deleted
    protected static function booted()
    {
        static::deleting(function ($invoice) {
            foreach ($invoice->items as $item) {
                if ($item->product) {
                    $product = $item->product;
                    $product->stock += $item->quantity; // restore stock
                    $product->save();
                }
            }
            // delete related invoice items
            $invoice->items()->delete();
        });
    }

}

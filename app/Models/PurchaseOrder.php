<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class PurchaseOrder extends Model 
{
    protected $fillable = ['supplier_id','po_number','po_date'];

    /**
     * Cast po_date to a Carbon instance automatically
     */
    protected $casts = [
        'po_date' => 'datetime',
    ];

    /**
     * Relationship: PurchaseOrder belongs to a Supplier
     */
    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Relationship: PurchaseOrder has many PurchaseItems
     */
    public function items() {
        return $this->hasMany(PurchaseItem::class);
    }

    /**
     * Optional helper to get formatted date
     */
    public function getFormattedDateAttribute() {
        return $this->po_date ? $this->po_date->format('Y-m-d') : null;
    }

    /**
     * Optional helper to calculate total amount of this order
     */
    public function getTotalAmountAttribute() {
        return $this->items->sum('total');
    }
}

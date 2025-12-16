<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    // Mass assignable fields
    protected $fillable = ['name', 'contact', 'gst_number', 'address'];

    // Relationship: A supplier can have many purchase orders
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'company_name', 'email', 'phone', 
        'address', 'gst_number', 'payment_terms', 'opening_balance'
    ];

    public function purchases() {
        return $this->hasMany(PurchaseOrder::class);
    }
}

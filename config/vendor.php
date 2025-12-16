<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'vendors';

    protected $fillable = [
        'name',             // Supplier Name
        'company_name',     // Organization Name
        'address',          // Address
        'email',            // Email
        'phone',            // Phone
        'gst_number',       // GST / TAX No.
        'payment_type',     // Cash / UPI / Credit Card etc.
    ];
}

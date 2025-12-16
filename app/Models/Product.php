<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'sub_category',
        'brand',
        'size',
        'hsn_code',
        'purchase_price',
        'price',
        'stock',
        'min_stock',
        'barcode',
        'barcode',
        'image',
    ];

    // 🔹 BRAND RELATIONSHIP
    public function brandName()
    {
        return $this->belongsTo(Brand::class, 'brand', 'id');
    }

    // 🔹 CATEGORY RELATIONSHIP
    public function categoryName()
    {
        return $this->belongsTo(Category::class, 'category', 'id');
    }

    // 🔹 SUB CATEGORY RELATIONSHIP
    public function subCategoryName()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category', 'id');
    }

    // 🔹 SIZE RELATIONSHIP
    public function sizeName()
    {
        return $this->belongsTo(Size::class, 'size', 'id');
    }
}

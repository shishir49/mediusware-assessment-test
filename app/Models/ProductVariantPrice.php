<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Variant;

class ProductVariantPrice extends Model
{
    protected $fillable = [
        'price',
        'stock',
        'product_id',
        'product_variant_one',
        'product_variant_two',
        'product_variant_three'
    ];
    
    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }
}

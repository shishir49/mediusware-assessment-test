<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductVariantPrice;

class Product extends Model
{
    protected $fillable = [
        'title', 'sku', 'description'
    ];

    public function variantGroup()
    {
        return $this->hasMany(ProductVariantPrice::class);
    }

    public function productVariant() {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;

class Variant extends Model
{
    protected $fillable = [
        'title', 'description'
    ];

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function productVariantPrice()
    {
        return $this->hasMany(ProductVariantPrice::class);
    }
}

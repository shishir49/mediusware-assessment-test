<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Variant;

class ProductVariant extends Model
{
    protected $fillable = [
        'variant',
        'variant_id',
        'product_id'
    ];
    public function variants()
    {
        return $this->belongsTo(Variant::class, 'variant_id');
    }
}

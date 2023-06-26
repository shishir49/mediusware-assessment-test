<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Variant;

class ProductVariantPrice extends Model
{
    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }
}

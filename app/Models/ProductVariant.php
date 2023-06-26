<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Variant;

class ProductVariant extends Model
{
    public function variants()
    {
        return $this->belongsTo(Variant::class, 'variant_id');
    }
}

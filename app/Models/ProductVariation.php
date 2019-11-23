<?php

namespace App\Models;

use App\Custom\CustomModel;

class ProductVariation extends CustomModel
{
    //
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}

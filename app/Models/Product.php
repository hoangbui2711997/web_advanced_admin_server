<?php

namespace App\Models;

use App\Custom\CustomModel;

class Product extends CustomModel
{
    protected $with = ['variations', 'discount', 'category'];
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image_url',
        'rate',
        'rate_amount',
        'price_base',
        'price',
        'special_from',
        'special_to',
        'amount_in_stock',
        'discount_id',
        'category_id',
    ];

    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function discount ()
    {
        return $this->belongsTo(Discount::class, 'discount_id');
    }

    public function category ()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}

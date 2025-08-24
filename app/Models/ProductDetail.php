<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    protected $fillable = [
        'product_id','image','gender_id','category_id','weight','age_type','age'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

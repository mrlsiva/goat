<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'unique_id','status'
    ];

    public function details()
    {
        return $this->hasMany(ProductDetail::class)->where('is_delete', 0);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'user_id','unique_id','status','is_delete'
    ];

    public function details()
    {
        return $this->hasMany(ProductDetail::class)->where('is_delete', 0);
    }
}

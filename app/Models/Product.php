<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'user_id','unique_id','status','is_delete','unique_number'
    ];

    public function details()
    {
        return $this->hasMany(ProductDetail::class)->where('is_delete', 0);
    }

    public function latestDetail()
    {
        return $this->hasOne(ProductDetail::class)
                    ->where('is_delete', 0)
                    ->latestOfMany();
    }

    public function getStatusTextAttribute()
    {
        return match ($this->status) {
            1 => 'Active',
            2 => 'No More',
            3 => 'Sold Out',
            default => 'Unknown',
        };
    }

}

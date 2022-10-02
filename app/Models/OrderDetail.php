<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'product_id',
        'order_id',
    ];

    function product()
    {
        return $this->belongsTo(Product::class);
    }
}

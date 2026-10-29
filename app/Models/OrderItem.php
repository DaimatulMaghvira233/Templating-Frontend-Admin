<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'produk_id', 'quantity', 'price', 'subtotal'];

    public function product()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}

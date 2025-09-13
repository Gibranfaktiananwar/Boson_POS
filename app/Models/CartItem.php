<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', // pastikan ini ada
        'quantity',
        'price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);  // Relasi one-to-many inverse
    }
}

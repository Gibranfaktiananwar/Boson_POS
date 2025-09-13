<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = ['code', 'name', 'stock', 'description', 'price', 'image'];

    public function category()
    {
        //devine relationship between products and categories
        return $this->belongsTo(Category::class, 'code', 'code');
    }
}

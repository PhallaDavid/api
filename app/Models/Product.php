<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image_url',
        'sku',
        'price',
        'stock',
        'is_active',
        'category_id',
        'subcategory_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

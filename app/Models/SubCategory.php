<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    protected $fillable = [
        'name', 'description', 'image', 'is_active', 'parent_id'
    ];

    public function parent()
    {
        return $this->belongsTo(\App\Models\Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(\App\Models\Product::class);
    }
}


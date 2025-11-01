<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'is_new',
        'category',
        'name',
        'image',
        'description',
        'price',
        'box_content',
        'detailed_description',
        'stock',
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use function PHPUnit\Framework\returnArgument;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
            'is_new',
        'category',
        'name',
        'slug',
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

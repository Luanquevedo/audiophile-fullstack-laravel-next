<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'subtotal', 'discount', 'delivery_fee', 'total', 'status', 'shipping_address'];
    
    protected $casts = ['shipping-address' => 'array'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

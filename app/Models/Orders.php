<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $fillable = [
        'order_id',
        'customer_id',
        'sub_total',
        'discount',
        'gst',
        'grand_total',
    ];
}

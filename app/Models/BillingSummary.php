<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingSummary extends Model
{
    protected $table = 'billing_summary';
    protected $fillable = ['order_id', 'product_description', 'qty', 'price', 'total'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerInformation extends Model
{
    protected $fillable = ['name', 'email', 'city', 'contact'];
}

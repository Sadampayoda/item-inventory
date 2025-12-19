<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'code',
        'image',
        'name',
        'description',
        'warehouse',
        'stock_on_hand',
        'stock_avalaible',
        'min_stock',
        'is_active',
    ];
}

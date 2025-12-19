<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InboundDetail extends Model
{
    protected $fillable = [
        'inbound_id',
        'item_id',
        'item_name',
        'quantity'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}

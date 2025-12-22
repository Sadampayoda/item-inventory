<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutboundDetail extends Model
{
    protected $fillable = [
        'outbound_id',
        'item_id',
        'item_name',
        'quantity'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}

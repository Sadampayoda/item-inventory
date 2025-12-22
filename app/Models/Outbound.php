<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Outbound extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'transaction_number',
        'transaction_date',
        'warehouse_id',
        'status',
        'expired_date'
    ];

    public function details()
    {
        return $this->hasMany(OutboundDetail::class);
    }

    public function warehouseRef()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
}

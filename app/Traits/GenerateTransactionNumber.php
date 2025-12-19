<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait GenerateTransactionNumber
{
    /**
     * Generate transaction number
     *
     * @param string $prefix
     * @param string $table
     * @param string $column
     * @return string
     */
    public function generateTransactionNumber(
        string $prefix,
        string $table,
        string $column = 'transaction_number'
    ): string {
        $date = Carbon::now();
        $yearMonth = $date->format('ym');

        $lastNumber = DB::table($table)
            ->where($column, 'like', "{$prefix}-{$yearMonth}%")
            ->orderBy($column, 'desc')
            ->value($column);

        if ($lastNumber) {
            $lastSeq = (int) substr($lastNumber, -4);
            $nextSeq = str_pad($lastSeq + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextSeq = '0001';
        }

        return "{$prefix}-{$yearMonth}{$nextSeq}";
    }
}

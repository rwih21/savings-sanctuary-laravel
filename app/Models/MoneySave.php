<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoneySave extends Model
{
    protected $fillable = [
        "date_saved",
        "amount_bf_saved",
        "amount_gf_saved", 
    ];

    public function getTotalAttribute(): int {
        return $this->amount_bf_saved + $this->amount_gf_saved;
    }

    public function getSurplusAttribute(): int {
        return $this->total - 92000;
    }

    // public static function formatCurrency(int $value): string 
    // {
    //     if ($value >= 1000000) return round($value / 1000000) . 'M';
    //     if ($value >= 1000) return round($value / 1000) . 'k';
    //     return (string) $value;
    // }
    public static function formatCurrency(?int $value): string
    {
        if ($value === null) {
            return '-';
        }

        return 'Rp ' . number_format($value);
    }
}

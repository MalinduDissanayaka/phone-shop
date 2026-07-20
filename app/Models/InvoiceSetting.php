<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceSetting extends Model
{
    protected $fillable = [
        'prefix',
        'next_number',
        'tax_rate',
        'currency',
        'footer_note',
    ];

    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }
}

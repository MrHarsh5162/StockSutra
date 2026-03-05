<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NightClosing extends Model
{
    protected $fillable = [
        'item_id',
        'opening_quantity',
        'closing_quantity',
        'consumed_quantity',
        'entry_date'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}

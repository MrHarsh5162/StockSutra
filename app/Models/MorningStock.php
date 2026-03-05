<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MorningStock extends Model
{
    /** @use HasFactory<\Database\Factories\MorningStockFactory> */
    use HasFactory;

    protected $fillable = ['item_id', 'vendor_id', 'quantity_received', 'entry_date'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}

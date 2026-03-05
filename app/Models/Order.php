<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = ['item_id', 'quantity', 'date', 'received'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}

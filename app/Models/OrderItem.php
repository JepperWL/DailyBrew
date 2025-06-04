<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'order_id',
        'beverage_id',
        'quantity',
        'note'
    ];
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function beverage()
    {
        return $this->belongsTo(Beverage::class);
    }
}

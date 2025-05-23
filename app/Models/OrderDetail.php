<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends BaseModel
{
    use HasFactory;


    protected $fillable = [
        'order_id',
        'stock_detail_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function stockDetail()
    {
        return $this->belongsTo(StockDetail::class);
    }
}

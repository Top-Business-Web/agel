<?php

namespace App\Models;

class Order extends BaseModel
{
    protected $fillable = [];
    protected $casts = [];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function investor()
    {
        return $this->belongsTo(Investor::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    

}

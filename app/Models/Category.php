<?php

namespace App\Models;

class Category extends BaseModel
{
    protected $fillable = [];
    protected $casts = [];

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function office()
    {
        $vendor=Vendor::where('id', $this->vendor_id)->first();
        return $vendor->parent_id == null ? $vendor->id : $vendor->parent_id;
    }
}

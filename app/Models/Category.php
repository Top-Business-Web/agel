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

}

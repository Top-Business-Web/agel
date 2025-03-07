<?php

namespace App\Models;

class Branch extends BaseModel
{
    protected $fillable = ['name', 'city_id', 'status'];
    protected $casts = [];


    public function city()
    {
        return $this->belongsTo(City::class);
    }
}

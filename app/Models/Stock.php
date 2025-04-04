<?php

namespace App\Models;

class Stock extends BaseModel
{
    protected $fillable = [];
    protected $casts = [];


    public function operations()
    {
        return $this->hasMany(Operation::class);
    }

}

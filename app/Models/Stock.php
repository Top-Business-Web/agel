<?php

namespace App\Models;

class Stock extends BaseModel
{
    protected $fillable = [];
    protected $casts = [];


    public function operation()
    {
        return $this->belongsTo(Operation::class);



    }
}

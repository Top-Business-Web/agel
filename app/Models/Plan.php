<?php

namespace App\Models;

class Plan extends BaseModel
{
    protected $fillable = ['name','price','period','description','image','discount','status'];
    protected $casts = [];

}

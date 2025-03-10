<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branch extends BaseModel
{
    use HasFactory;
    protected $fillable = ['name', 'city_id', 'status'];
    protected $casts = [];


    public function city()
    {
        return $this->belongsTo(City::class);
    }
}

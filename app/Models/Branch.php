<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branch extends BaseModel
{
    use HasFactory;
    protected $fillable = ['name', 'region_id', 'status'];
    protected $casts = [];


    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}

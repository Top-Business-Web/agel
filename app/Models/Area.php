<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends BaseModel
{
    use HasFactory;

    public function city()
    {

        return $this->belongsTo(City::class);
    }
}

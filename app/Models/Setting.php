<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends BaseModel
{
    use HasFactory;
    protected $fillable = [
        'key',
        'value',
        'guard',
        'vendor_id',
    ];
    protected $casts = [];

}

<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends BaseModel
{
    use HasFactory;

    protected $fillable = [];
    protected $casts = [];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}

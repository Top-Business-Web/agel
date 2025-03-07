<?php

namespace App\Models;

use App\Models\BaseModel;
use Spatie\Translatable\HasTranslations;


class City extends BaseModel
{



    protected $fillable = ['name', 'status', 'country_id'];
    protected $casts = [];




    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}

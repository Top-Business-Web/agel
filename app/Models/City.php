<?php

namespace App\Models;
use App\Models\BaseModel;
use Spatie\Translatable\HasTranslations;


class City extends BaseModel
{

    use HasTranslations;

    public array $translatable = [
        'name',
    ];

    protected $fillable = ['name','status','country_id','location'];
    protected $casts = [];




    public function country()
    {
        return $this->belongsTo(Country::class);
    }

   

}

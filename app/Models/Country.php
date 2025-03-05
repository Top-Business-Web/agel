<?php

namespace App\Models;
use App\Models\BaseModel;
use Spatie\Translatable\HasTranslations;


class Country extends BaseModel
{

    use HasTranslations;

    public array $translatable = [
        'name',
    ];
    protected $fillable = ['name','status','location'];
    protected $casts = [];





}

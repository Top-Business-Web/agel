<?php

namespace App\Models;
use App\Models\BaseModel;
use Spatie\Translatable\HasTranslations;


class Country extends BaseModel
{

    protected $fillable = ['name','status'];
    protected $casts = [];





}

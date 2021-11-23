<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model implements TranslatableContract
{
    use Translatable;
    protected $guarded = ['created_at','updated_at'];
    public $translatedAttributes = ['name'];
}

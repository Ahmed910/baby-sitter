<?php

namespace App\Models;

use App\Traits\Uuid;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class District extends Model implements TranslatableContract
{
    use Translatable,Uuid;
    protected $guarded = ['created_at','updated_at'];
    public $translatedAttributes = ['name'];

}

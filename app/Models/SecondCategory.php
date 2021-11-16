<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class SecondCategory extends Model implements TranslatableContract
{
    use Translatable;
    protected $guarded = ['created_at','updated_at'];
    public $translatedAttributes = ['name'];

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }
}

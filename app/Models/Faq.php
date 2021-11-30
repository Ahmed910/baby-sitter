<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Faq extends Model implements TranslatableContract
{
    use Translatable;
    protected $guarded = ['created_at','updated_at'];
    public $translatedAttributes = ['question','answer'];


     // Scopes
     public function scopeActive($query)
     {
         $query->where('is_active' , 1);
     }

}

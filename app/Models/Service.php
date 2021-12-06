<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Service extends Model implements TranslatableContract
{
    use Translatable;
    protected $guarded = ['created_at','updated_at'];
    public $translatedAttributes = ['name'];

    public function user_services()
    {
        return $this->hasMany(UserService::class,'service_id');
    }
}

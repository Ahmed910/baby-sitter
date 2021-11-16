<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Uuid;

class Role extends Model implements TranslatableContract
{
    use Translatable, SoftDeletes , HasFactory, Uuid;

    protected $guarded = ['created_at','updated_at','deleted_at'];
    public $translatedAttributes = ['name','desc'];

    public function users()
    {
    	return $this->hasMany(User::class);
    }
    public function permissions()
    {
    	return $this->belongsToMany(Permission::class)->withTimestamps();
    }
}

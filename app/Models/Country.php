<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class Country extends Model implements TranslatableContract
{
    use Translatable, SoftDeletes, Uuid;
    protected $guarded = ['created_at','updated_at','deleted_at'];
    public $translatedAttributes = ['name','nationality'];

    protected static function boot()
    {
        static::saved(function ($data) {
            if (request()->hasFile('image')) {
                if ($data->media()->exists()) {
                    $image = AppMedia::where(['app_mediaable_type' => 'App\Models\Country','app_mediaable_id' => $data->id ,'media_type' => 'image'])->first();
                    $image->delete();
                    if (file_exists(storage_path('app/public/images/country/'.$image->media))){
                        \File::delete(storage_path('app/public/images/country/'.$image->media));
                        $image->delete();
                    }
                }
                $image = uploadImg(request()->image,'country');
                $data->media()->create(['media' => $image,'media_type' => 'image']);
            }
        });

        static::deleted(function ($data) {
            if ($data->media()->exists()) {
                $image = AppMedia::where(['app_mediaable_type' => 'App\Models\Country','app_mediaable_id' => $data->id ,'media_type' => 'image'])->first();
                if (file_exists(storage_path('app/public/images/country/'.$image->media))){
                    \File::delete(storage_path('app/public/images/country/'.$image->media));
                }
                $image->delete();
            }
        });
    }

    public function getImageAttribute()
    {
        $image = $this->media()->exists() ? 'storage/images/country/'.$this->media()->first()->media : 'dashboardAssets/images/cover/cover_sm.png';
        return asset($image);
    }

    // Relations
    // ========================= Image ===================
    public function media()
    {
    	return $this->morphOne(AppMedia::class,'app_mediaable');
    }

    public function cities()
    {
    	return $this->hasMany(City::class);
    }

    public function users()
    {
    	return $this->hasManyThrough(User::class,Profile::class);
    }

}

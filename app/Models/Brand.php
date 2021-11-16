<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class Brand extends Model implements TranslatableContract
{
    use Translatable , SoftDeletes , Uuid;
    protected $guarded = ['created_at','updated_at','deleted_at'];
    public $translatedAttributes = ['name'];

    protected static function boot()
    {
        static::saved(function ($data) {
            if (request()->hasFile('image')) {
                if ($data->media()->exists()) {
                    $image = AppMedia::where(['app_mediaable_type' => 'App\Models\Brand','app_mediaable_id' => $data->id ,'media_type' => 'image'])->first();
                    $image->delete();
                    if (file_exists(storage_path('app/public/images/brand/'.$image->media))){
                        \File::delete(storage_path('app/public/images/brand/'.$image->media));
                    }
                }
                $image = uploadImg(request()->image,'brand');
                $data->media()->create(['media' => $image,'media_type' => 'image']);
            }
        });

        static::deleted(function ($data) {
            if ($data->media()->exists()) {
                $image = AppMedia::where(['app_mediaable_type' => 'App\Models\Brand','app_mediaable_id' => $data->id ,'media_type' => 'image'])->first();
                if (file_exists(storage_path('app/public/images/brand/'.$image->media))){
                    \File::delete(storage_path('app/public/images/brand/'.$image->media));
                }
                $image->delete();
            }
        });
    }

    public function getImageAttribute()
    {
        $image = $this->media()->exists() ? 'storage/images/brand/'.$this->media()->first()->media : 'dashboardAssets/images/cover/cover_sm.png';
        return asset($image);
    }
    // Relations
    // ========================= Image ===================
    public function media()
    {
    	return $this->morphOne(AppMedia::class,'app_mediaable');
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function carModels()
    {
        return $this->hasMany(CarModel::class,'brand_id');
    }

}

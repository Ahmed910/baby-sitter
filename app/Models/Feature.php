<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Feature extends Model implements TranslatableContract
{
    use Translatable;
    protected $guarded = ['created_at','updated_at'];
    public $translatedAttributes = ['name'];

    protected static function boot()
    {
        parent::boot();
        static::saved(function ($data) {
            if (request()->hasFile('icon')) {
                if ($data->media()->exists()) {
                    $image = AppMedia::where(['app_mediaable_type' => 'App\Models\Feature','app_mediaable_id' => $data->id ,'media_type' => 'image'])->first();
                    $image->delete();
                    if (file_exists(storage_path('app/public/images/features/'.$image->media))){
                        \File::delete(storage_path('app/public/images/features/'.$image->media));
                        $image->delete();
                    }
                }
                $image = uploadImg(request()->image,'features');
                $data->media()->create(['media' => $image,'media_type' => 'image']);
            }
        });

        static::deleted(function ($data) {
            if ($data->media()->exists()) {
                $image = AppMedia::where(['app_mediaable_type' => 'App\Models\Feature','app_mediaable_id' => $data->id ,'media_type' => 'image'])->first();
                if (file_exists(storage_path('app/public/images/features/'.$image->media))){
                    \File::delete(storage_path('app/public/images/features/'.$image->media));
                }
                $image->delete();
            }
        });
    }

    public function getIconAttribute()
    {
        $image = $this->media()->exists() ? 'storage/images/features/'.$this->media()->first()->media : 'dashboardAssets/images/icons/logo_sm.png';
        return asset($image);
    }


     // Relations
     public function media()
     {
         return $this->morphOne(AppMedia::class,'app_mediaable');
     }
}

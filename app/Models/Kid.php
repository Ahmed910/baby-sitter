<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kid extends Model
{
    protected $guarded = ['created_at','updated_at'];

    protected static function boot()
    {
        parent::boot();
        static::saved(function ($data) {
            if (request()->hasFile('image')) {
                if ($data->media()->exists()) {
                    $image = AppMedia::where(['app_mediaable_type' => 'App\Models\Kid','app_mediaable_id' => $data->id ,'media_type' => 'image'])->first();
                    $image->delete();
                    if (file_exists(storage_path('app/public/images/kid/'.$image->media))){
                        \File::delete(storage_path('app/public/images/kid/'.$image->media));
                        $image->delete();
                    }
                }
                $image = uploadImg(request()->image,'kid');
                $data->media()->create(['media' => $image,'media_type' => 'image']);
            }
        });

        static::deleted(function ($data) {
            if ($data->media()->exists()) {
                $image = AppMedia::where(['app_mediaable_type' => 'App\Models\Kid','app_mediaable_id' => $data->id ,'media_type' => 'image'])->first();
                if (file_exists(storage_path('app/public/images/kid/'.$image->media))){
                    \File::delete(storage_path('app/public/images/kid/'.$image->media));
                }
                $image->delete();
            }
        });
    }

    public function getImageAttribute()
    {
        $image = $this->media()->exists() ? 'storage/images/kid/'.$this->media()->first()->media : 'dashboardAssets/images/icons/logo_sm.png';
        return asset($image);
    }


     // Relations
     public function media()
     {
         return $this->morphOne(AppMedia::class,'app_mediaable');
     }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slider extends Model
{

    protected $guarded = ['created_at','updated_at','deleted_at'];
    protected static function boot()
    {
        parent::boot();
        static::saved(function ($data) {

            if (request()->hasFile('file')) {
                if ($data->media()->exists()) {
                    $file = AppMedia::where(['app_mediaable_type' => 'App\Models\Slider','app_mediaable_id' => $data->id ,'media_type' => 'image'])->orWhere(['app_mediaable_type' => 'App\Models\Slider','app_mediaable_id' => $data->id ,'media_type' => 'video'])->first();
                    $file->delete();
                    if (file_exists(storage_path('app/public/images/slider/'.$file->media))){
                        \File::delete(storage_path('app/public/images/slider/'.$file->media));
                        $file->delete();
                    }
                    if (file_exists(storage_path('app/public/files/slider/'.$file->media))){
                        \File::delete(storage_path('app/public/files/slider/'.$file->media));
                        $file->delete();
                    }
                }

                if (strtok(request()->file->getClientMimeType(), "/") === 'video') {
                    $file = uploadFile(request()->file, 'slider');
                    $data->media()->create(['media' => $file,'media_type' => 'video']);
                }else{
                    $file = uploadImg(request()->file, 'slider');
                    $data->media()->create(['media' => $file,'media_type' => 'image']);
                }
            }
        });

        static::deleted(function ($data) {
            if ($data->media()->exists()) {
                $file = AppMedia::where(['app_mediaable_type' => 'App\Models\Slider','app_mediaable_id' => $data->id ,'media_type' => 'image'])->orWhere(['app_mediaable_type' => 'App\Models\Slider','app_mediaable_id' => $data->id ,'media_type' => 'video'])->first();
                if (file_exists(storage_path('app/public/images/slider/'.$file->media))){
                    \File::delete(storage_path('app/public/images/slider/'.$file->media));
                }
                if (file_exists(storage_path('app/public/files/slider/'.$file->media))){
                    \File::delete(storage_path('app/public/files/slider/'.$file->media));
                }
                $file->delete();
            }
        });
    }

    public function getFileAttribute()
    {
        if($this->media()->exists() && $this->media()->first()->media_type == 'video'){
            $file = $this->media()->exists() ? 'storage/files/slider/'.$this->media()->first()->media : 'dashboardAssets/images/cover/cover_sm.png';
            return asset($file);
        }
        $file = $this->media()->exists() ? 'storage/images/slider/'.$this->media()->first()->media : 'dashboardAssets/images/cover/cover_sm.png';
        return asset($file);
    }

     // ========================= Image ===================
     public function media()
     {
         return $this->morphOne(AppMedia::class,'app_mediaable');
     }

     // ============== scopes ==============
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildCentre extends Model
{
    protected $guarded = ['created_at','updated_at'];
    protected static function boot()
    {
        parent::boot();
        static::saved(function ($data) {
            if (request()->hasFile('business_license_image')) {
                if ($data->media()->where('option', 'business_license_image')->exists()) {
                    $image = AppMedia::where(['app_mediaable_type' => 'App\Models\ChildCentre','app_mediaable_id' => $data->id ,'media_type' => 'image','option' => 'business_license_image'])->first();
                    if ($image) {
                        if (file_exists(storage_path('app/public/images/child_centre/'.$image->media))) {
                            \File::delete(storage_path('app/public/images/child_centre/'.$image->media));
                            $image->delete();
                        }
                        $image->delete();
                    }
                }
                $image = uploadImg(request()->business_license_image, 'child_centre');
                $data->media()->create(['media' => $image,'media_type' => 'image','option' => 'business_license_image']);
            }
        });

        static::deleted(function ($data) {


        if ($data->media()->where(['option' => 'business_license_image'])->exists()) {
            $image = AppMedia::where(['app_mediaable_type' => 'App\Models\ChildCentre','app_mediaable_id' => $data->id , 'option' => 'business_license_image'])->first();
            $image->delete();
            if (file_exists(storage_path('app/public/images/child_centre/'.$image->media))){
                \File::delete(storage_path('app/public/images/child_centre/'.$image->media));
            }
        }

    });
    }

    public function getBusinessLicenseImageAttribute()
    {
        if($this->media()->where(['option' => 'business_license_image'])->exists()){
            return asset('storage/images/child_centre/'.$this->media()->where(['option' => 'business_license_image'])->first()->media);
        }
    }


     // Relations
     public function media()
     {
         return $this->morphOne(AppMedia::class,'app_mediaable');
     }
}

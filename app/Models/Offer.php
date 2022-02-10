<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $guarded = ['created_at','updated_at'];

    protected $dates = ['start_date' , 'end_date'];

    protected static function boot()
    {
        parent::boot();
        static::saved(function ($data) {
            if (request()->hasFile('photo')) {
                if ($data->media()->where('option', 'photo')->exists()) {
                    $image = AppMedia::where(['app_mediaable_type' => 'App\Models\Offer','app_mediaable_id' => $data->id ,'media_type' => 'image','option' => 'photo'])->first();
                    if ($image) {
                        if (file_exists(storage_path('app/public/images/offer/'.$image->media))) {
                            \File::delete(storage_path('app/public/images/offer/'.$image->media));
                            $image->delete();
                        }
                        $image->delete();
                    }
                }
                $image = uploadImg(request()->photo, 'offer');
                $data->media()->create(['media' => $image,'media_type' => 'image','option' => 'photo']);
            }
        });

        static::deleted(function ($data) {


        if ($data->media()->where(['option' => 'photo'])->exists()) {
            $image = AppMedia::where(['app_mediaable_type' => 'App\Models\Offer','app_mediaable_id' => $data->id , 'option' => 'photo'])->first();
            $image->delete();
            if (file_exists(storage_path('app/public/images/offer/'.$image->media))){
                \File::delete(storage_path('app/public/images/offer/'.$image->media));
            }
        }

    });
    }


    public function getPhotoAttribute()
    {
        if($this->media()->where(['option' => 'photo'])->exists()){
            return asset('storage/images/offer/'.$this->media()->where(['option' => 'photo'])->first()->media);
        }
    }


    // Scopes

    public function scopeOfferUser($query)
    {
        $query->where('user_id',auth('api')->id());
    }

     // Relations
     public function media()
     {
         return $this->morphOne(AppMedia::class,'app_mediaable');
     }

     public function user()
     {
         return $this->belongsTo(User::class);
     }

     public function getOfferPriceAttribute()
     {
         return $this->offer_fees * ($this->discount/100);
     }

}

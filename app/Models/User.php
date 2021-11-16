<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Uuid;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable ,HasFactory ,Uuid;

    protected $guarded = ['created_at','updated_at','deleted_at'];
    protected $appends = ['avatar','image'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime' , 'phone_verified_at' => 'datetime'];
    protected $dates = ['date_of_birth' , 'date_of_birth_hijri'];

    protected static function boot()
    {
        // parent::boot();
        // static::booted();
        static::saved(function ($data) {

            if (!isset($data['qr_code']) || !$data['qr_code'] != "") {

                if (!\File::isDirectory(storage_path('app/public/images/user/'))){
                    \File::makeDirectory(storage_path('app/public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'user'.DIRECTORY_SEPARATOR), 0777, true);
                }
                $file_name = time()."_".$data->id."_qr_code.png";
                \QrCode::errorCorrection('H')
                    ->format('png')
                    ->encoding('UTF-8')
                  //  ->merge(public_path('dashboardAssets/images/cover/cover_sm.png'), .2 ,true)
                    ->size(500)
                    ->color(0,0,255)
                    ->generate($data->id, storage_path('app/public/images/user/'.$file_name));
                $data['qr_code'] = $file_name;
                $data->save();
            }


            if (request()->hasFile('certificates')) {
                if ($data->media()->where('option' , 'certificates')->exists()) {
                    $image = AppMedia::where(['app_mediaable_type' => 'App\Models\User','app_mediaable_id' => $data->id])->first();
                    $image->delete();
                    if (file_exists(storage_path('app/public/images/user/'.$image->media))){
                        \File::delete(storage_path('app/public/images/user/'.$image->media));
                    }elseif (file_exists(storage_path('app/public/files/user/'.$image->media))){
                        \File::delete(storage_path('app/public/files/user/'.$image->media));
                    }
                }
                if (request()->certificates->getClientMimeType() == 'application/pdf') {
                    $image = uploadFile(request()->certificates,'user');
                    $data->media()->create(['media' => $image,'media_type' => 'file','option' => 'certificates']);
                }elseif (in_array(request()->certificates->getClientMimeType(),['image/jpeg','image/jpg','image/gif','image/png','image/bmp','image/svg+xml'])) {
                    $image = uploadImg(request()->certificates,'user');
                    $data->media()->create(['media' => $image,'media_type' => 'image','option' => 'certificates']);
                }
            }
        });


        static::deleted(function ($data) {
            if (file_exists(storage_path('app/public/images/user/'. $data->image))) {
                \File::delete(storage_path('app/public/images/user/'. $data->image));
            }
            if (file_exists(storage_path('app/public/images/user/'. $data->cover))) {
                \File::delete(storage_path('app/public/images/user/'. $data->cover));
            }

            if ($data->media()->where(['option' => 'certificates'])->exists()) {
                $image = AppMedia::where(['app_mediaable_type' => 'App\Models\User','app_mediaable_id' => $data->id , 'option' => 'certificates'])->first();
                $image->delete();
                if (file_exists(storage_path('app/public/images/user/'.$image->media))) {
                    \File::delete(storage_path('app/public/images/user/'.$image->media));
                } elseif (file_exists(storage_path('app/public/files/user/'.$image->media))) {
                    \File::delete(storage_path('app/public/files/user/'.$image->media));
                }
            }
        });
    }


    public function getQrCodeAttribute()
    {

        if (isset($this->attributes['qr_code']) && $this->attributes['qr_code']) {
            return asset('storage/images/user') . '/' . $this->attributes['qr_code'];
        } else {
            return '';
        }

    }

    public function getCertificatesAttribute()
    {
        if($this->media()->exists() && $this->media()->first()->media_type == 'image'){
            return asset('storage/images/user/'.$this->media()->first()->media);
        }elseif ($this->media()->exists() && $this->media()->first()->media_type == 'file') {
            return asset('storage/files/user/'.$this->media()->first()->media);
        }
    }

    public function getCertificateTypeAttribute()
    {
        if($this->media()->where(['option' => 'certificates'])->exists() && $this->media()->where(['option' => 'certificates'])->first()->media_type == 'image'){
            return 'image';
        }elseif ($this->media()->where(['option' => 'certificates'])->exists() && $this->media()->where(['option' => 'certificates'])->first()->media_type == 'file') {
            return 'file';
        }
    }




    // Setter & Getter Attributes


    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    public function setImageAttribute($value)
    {
        if ($value && $value->isValid()) {
            if (isset($this->attributes['image']) && $this->attributes['image']) {
                if (file_exists(storage_path('app/public/images/user/'. $this->attributes['image']))) {
                    \File::delete(storage_path('app/public/images/user/'. $this->attributes['image']));
                }
            }
            $image = uploadImg($value,'user');
            $this->attributes['image'] = $image;
        }
    }

    public function setCoverAttribute($value)
    {
        if ($value && $value->isValid()) {
            if (isset($this->attributes['cover']) && $this->attributes['cover']) {
                if (file_exists(storage_path('app/public/images/user/'. $this->attributes['cover']))) {
                    \File::delete(storage_path('app/public/images/user/'. $this->attributes['cover']));
                }
            }
            $cover = uploadImg($value,'user');
            $this->attributes['cover'] = $cover;
        }
    }



    public function getImageAttribute()
    {
        $image = isset($this->attributes['image']) && $this->attributes['image'] ? 'storage/images/user/'.$this->attributes['image'] : 'dashboardAssets/images/backgrounds/avatar.jpg';
        return asset($image);
    }

    public function getCoverImageAttribute()
    {
        $image = $this->attributes['cover'] ? 'storage/images/user/'.$this->attributes['cover'] : "dashboardAssets/global/images/cover/consult-cover2.jpg";
        return asset($image);
    }

    public function getIsUserDeactiveAttribute()
    {
        return ! $this->attributes['is_active'] || $this->attributes['is_ban'];
    }

    public function getIsAvailableAttribute()
    {
        return $this->driver()->where(['is_available' => 1, 'is_admin_accept' => 1])->count() > 0;
    }



    public function getIsInfectedAttribute()
    {
        return optional($this->profile)->is_infected == true;
    }



    public function getAvatarAttribute()
    {
        $image = $this->attributes['image'] ? 'storage/images/user/'.$this->attributes['image'] : 'dashboardAssets/images/backgrounds/avatar.jpg';
        return asset($image);
    }

    public function getCountryNameAttribute()
    {
        return optional(@$this->profile->country)->name;
    }

    public function getCityNameAttribute()
    {
        return optional(@$this->profile->city)->name;
    }



    // Scopes
    public function scopeActive($query)
    {
        $query->where(['is_active' => 1 , 'is_ban' => 0 , 'is_admin_active_user' => 1]);
    }


    // Relations
    public function media()
    {
    	return $this->morphOne(AppMedia::class,'app_mediaable');
    }



    public function user_services()
    {
        return $this->hasMany(UserService::class,'user_id');
    }

    public function user_features()
    {
        return $this->hasMany(UserFeature::class,'user_id');
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class,'user_features','user_id','feature_id')->withTimestamps();
    }


    //==========================Devices==================
    public function devices()
    {
        return $this->hasMany(Device::class);
    }



    public function child_centre()
    {
        return $this->hasOne(ChildCentre::class);
    }


    public function country()
    {
        return $this->hasOneThrough(Country::class,Profile::class,'user_id','id','id','country_id');
    }



    // Orders
    // =====================Client Orders==================
    public function clientOrders()
    {
        return $this->hasMany(Order::class,'client_id');
    }





    //==========================Profile=====================
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    // Roles & Permissions
    public function role()
    {
        return $this->belongsTo(Role::class);
    }


    public function hasPermissions($route, $method = null)
    {
        if ($this->user_type == 'superadmin') {
             return true;
        }
        if (is_null($method)) {
               if ($this->role->permissions->contains('route_name',$route.".index")) {
                   return true;
               }elseif ($this->role->permissions->contains('route_name',$route.".store")) {
                   return true;
               }elseif ($this->role->permissions->contains('route_name',$route.".update")) {
                   return true;
               }elseif ($this->role->permissions->contains('route_name',$route.".destroy")) {
                   return true;
               }elseif ($this->role->permissions->contains('route_name',$route.".show")) {
                   return true;
               }elseif ($this->role->permissions->contains('route_name',$route.".wallet")) {
                   return true;
               }
           }else{
                return $this->role->permissions->contains('route_name',$route.".".$method);
           }
           return false;
    }

    // For Notification Channel
    public function receivesBroadcastNotificationsOn()
    {
        return 'babysitters-notification.' . $this->id;
    }

    /**
    * Get the identifier that will be stored in the subject claim of the JWT.
    *
    * @return mixed
    */
   public function getJWTIdentifier()
   {
       return $this->getKey();
   }

   /**
    * Return a key value array, containing any custom claims to be added to the JWT.
    *
    * @return array
    */
   public function getJWTCustomClaims()
   {
       return [];
   }

   public function routeNotificationForFcm($notification)
   {
<<<<<<< HEAD
       if ($this->attributes['user_type'] == 'child_centre') {
           return @$this->devices->last()->device_token;
       }
=======

>>>>>>> 2d846db37f40ce2cf1786733c1c52d69cbaec735
       return $this->devices->pluck('device_token')->toArray();
   }

}

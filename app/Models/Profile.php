<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Profile extends Model
{
    use Uuid;
    protected $guarded = ['created_at', 'updated_at'];
    protected $dates = ['last_login_at'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'added_by_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }


    // Scopes

    public function scopeNearest($query, $lat, $lng, $qr = null)
    {
        $lat = (float)$lat;
        $lng = (float)$lng;
        $space_search_by = (float)(convertArabicNumber(setting('search_distance')) ? convertArabicNumber(setting('search_distance')) : 8);

        if ($qr) {
            $space_search_by = (float)(convertArabicNumber(setting('qr_search_distance')) ? convertArabicNumber(setting('qr_search_distance')) / 1000 : 5 / 1000);
            // dd($space_search_by) ;
        }
        //   dd($space_search_by , $lat , $lng , $this  , $this->lng);
        $query->select(\DB::raw("*,
             (6371 * ACOS(COS(RADIANS($lat))
             * COS(RADIANS(lat))
             * COS(RADIANS($lng) - RADIANS(lng))
             + SIN(RADIANS($lat))
             * SIN(RADIANS(lat)))) AS distance"))
            ->having('distance', '<=', $space_search_by)
            ->orderBy('distance', 'asc');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteTime extends Model
{
    protected $dates = ['time'];
    protected $guarded = ['created_at','updated_at'];


    public function availableDay()
    {
        return $this->belongsTo(AvailableDay::class,'available_day_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class,'district_id');
    }

}

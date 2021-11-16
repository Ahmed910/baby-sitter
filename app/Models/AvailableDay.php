<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class AvailableDay extends Model 
{
    
    protected $guarded = ['created_at','updated_at'];
   


    public function district()
    {
        return $this->belongsTo(District::class,'district_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFeature extends Model
{
    protected $guarded = ['created_at','updated_at'];

    public function feature()
    {
        return $this->belongsTo(Feature::class,'feature_id');
    }
}

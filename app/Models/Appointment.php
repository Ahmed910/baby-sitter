<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{

    protected $guarded = ['created_at','updated_at'];
    protected $casts = ['from' => 'datetime' , 'to' => 'datetime'];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}

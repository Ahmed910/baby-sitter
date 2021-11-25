<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $guarded = ['created_at','updated_at'];

    public function day()
    {
        return $this->belongsTo(Day::class,'day_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class,'appointment_id');
    }
}

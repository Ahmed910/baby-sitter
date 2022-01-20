<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderHour extends Model
{
    protected $guarded = ['created_at','updated_at'];
    protected $dates = ['date','start_time','end_time'];

   
}

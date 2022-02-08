<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderMonthDay extends Model
{
    protected $guarded = ['created_at','updated_at'];
    protected $dates = ['start_time','end_time'];
    public function day()
    {
        return $this->belongsTo(Day::class,'day_id');
    }

    public function month_dates()
    {
        return $this->hasMany(OrderMonthDate::class,'order_month_day_id');
    }
}

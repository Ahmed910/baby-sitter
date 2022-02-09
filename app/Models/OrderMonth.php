<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderMonth extends Model
{
    protected $guarded = ['created_at','updated_at'];
    protected $dates = ['start_date','end_date'];

    public function month_days()
    {
        return $this->hasMany(OrderMonthDay::class,'order_month_id');
    }

    public function month_dates()
    {
        return $this->hasManyThrough(OrderMonthDate::class, OrderMonthDay::class,'order_month_id','order_month_day_id');
    }



}

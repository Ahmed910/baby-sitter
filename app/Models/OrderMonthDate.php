<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderMonthDate extends Model
{
    protected $guarded = ['created_at','updated_at'];
    protected $dates = ['date'];

    public function order_day()
    {
        return $this->belongsTo(OrderMonthDay::class,'order_month_day_id');
    }
    public function month()
    {
        return $this->belongsTo(OrderMonth::class,'order_month_id');
    }
}

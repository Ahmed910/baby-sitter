<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CenterOrder extends Model
{

    protected $guarded = ['created_at','updated_at'];


    public function kids()
    {
        return $this->morphMany(OrderKid::class,'order_kidsable');
    }

    public function hours()
    {
        return $this->morphOne(OrderHour::class,'order_hoursable');
    }

    public function months()
    {
        return $this->morphOne(OrderMonth::class,'order_monthsable');
    }
    public function client()
    {
        return $this->belongsTo(User::class,'client_id');
    }
    public function center()
    {
        return $this->belongsTo(User::class,'center_id');
    }
    public function baby_sitter()
    {
        return $this->belongsTo(BabySitter::class,'baby_sitter_id');
    }
    public function service()
    {
        return $this->belongsTo(Service::class,'service_id');
    }
    public function main_order()
    {
        return $this->belongsTo(MainOrder::class,'main_order_id');
    }
}

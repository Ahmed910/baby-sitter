<?php

namespace App\Models;

use App\Traits\QrCode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SitterOrder extends Model
{
    use QrCode;
    protected $guarded = ['created_at', 'updated_at'];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($data) {

            if (!isset($data['qr_code']) || !$data['qr_code'] != "") {
                // dd('sss');
               self::generateQrCode('sitter_order',$data);
            }
        });
    }



    public function kids()
    {
        return $this->morphMany(OrderKid::class, 'order_kidsable');
    }

    public function hours()
    {
        return $this->morphOne(OrderHour::class, 'order_hoursable');
    }

    public function months()
    {
        return $this->morphOne(OrderMonth::class, 'order_monthsable');
    }
   
    public function client()
    {
        return $this->belongsTo(User::class,'client_id');
    }
    public function sitter()
    {
        return $this->belongsTo(User::class,'sitter_id');
    }
    public function service()
    {
        return $this->belongsTo(Service::class,'service_id');
    }
}

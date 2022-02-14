<?php

namespace App\Models;

use App\Traits\QrCode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class MainOrder extends Model
{
    use QrCode;
    protected $guarded = ['created_at','updated_at'];
    protected $dates = ['finished_at'];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($data) {

            if (!isset($data['qr_code']) || !$data['qr_code'] != "") {
                // dd('sss');
               self::generateQrCode('main_order',$data);
            }
        });
    }

    public function getQrCodeAttribute()
    {

        if (isset($this->attributes['qr_code']) && $this->attributes['qr_code']) {
            return asset('storage/images/main_order') . '/' . $this->attributes['qr_code'];
        } else {
            return '';
        }

    }

    public function sitter_order()
    {
        return $this->hasOne(SitterOrder::class,'main_order_id');
    }

    public function center_order()
    {
        return $this->hasOne(CenterOrder::class,'main_order_id');
    }
    public function client()
    {
        return $this->belongsTo(User::class,'client_id');
    }
    public function sitter()
    {
        return $this->belongsTo(User::class,'sitter_id');
    }

    public function center()
    {
        return $this->belongsTo(User::class,'center_id');
    }
    public function baby_sitter()
    {
        return $this->hasOneThrough(BabySitter::class,CenterOrder::class,'main_order_id','center_id');
    }
    public function chat()

    {
		return $this->hasOne(Chat::class,'order_id');
	}
}

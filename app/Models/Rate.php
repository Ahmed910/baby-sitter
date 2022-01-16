<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $guarded = ['created_at','updated_at'];


    public function order()
    {
        return $this->belongsTo(MainOrder::class,'order_id');
    }

    public function toUser()
    {
       return $this->belongsTo(User::class,'to');
    }
    public function fromUser()
    {
       return $this->belongsTo(User::class,'from');
    }
    public function toBabySitter()
    {
       return $this->belongsTo(BabySitter::class,'to_baby_sitter');
    }
    public function toClient()
    {
       return $this->belongsTo(User::class,'to_client');
    }
    public function toCenter()
    {
       return $this->belongsTo(User::class,'to_center');
    }

}

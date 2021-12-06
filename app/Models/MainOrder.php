<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainOrder extends Model
{
    protected $guarded = ['created_at','updated_at'];

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
}

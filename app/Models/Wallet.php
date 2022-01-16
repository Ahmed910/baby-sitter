<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $guarded = ['created_at','updated_at'];

    public function walletWithdraw()
    {
        return $this->belongsTo(WalletWithdraw::class,'wallet_withdraw_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function transfered_user()
    {
        return $this->belongsTo(User::class,'transferd_by');
    }
}

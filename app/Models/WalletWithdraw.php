<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletWithdraw extends Model
{
    protected $guarded = ['created_at','updated_at'];

    public function wallet()
    {
        return  $this->hasOne(Wallet::class,'wallet_withdraw_id');
    }
}

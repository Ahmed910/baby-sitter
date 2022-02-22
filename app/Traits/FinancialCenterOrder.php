<?php

namespace App\Traits;

use App\Models\MainOrder;
use App\Models\User;
use App\Models\Wallet;

trait FinancialCenterOrder
{
    // use Order;
    protected function updateFinancialCenterData(MainOrder $order,$center_order)
    {
        $order->update(['finished_at' => now()]);

        // isset($otp_code) && $otp_code ? $center_order->update(['status'=>'completed','otp_code'=>NULL]) : $center_order->update(['status'=>'completed']);
        $center_order->update(['status'=>'completed']);
        $center = User::findOrFail($center_order->center_id);
        $wallet_before = $center->wallet;
        $wallet_after = $wallet_before + $order->final_price;
        $center->update(['wallet'=>$wallet_after]);
        // $this->chargeWallet($order->final_price, $center_order->center_id);
        if ($center_order->pay_type == 'wallet') {
            Wallet::create(['amount' => $order->final_price, 'wallet_before' => $wallet_before, 'wallet_after' => $wallet_after, 'user_id' => $order->center_id, 'transferd_by' => $order->client_id, 'order_id' => $order->id]);
        }
    }
}
?>


<?php

namespace App\Traits;

use App\Models\{MainOrder,SitterOrder,User,Wallet};

trait CompleteOrderHourService
{
    use Order;
    public function completeOrderForHourService(MainOrder $order)
    {
        $sitter_order = SitterOrder::where(['status' => 'with_the_child',  'main_order_id' => $order->id])->firstOrFail();
            $order->update(['finished_at' => now()]);

            isset($otp_code) && $otp_code ? $sitter_order->update(['status'=>'completed','otp_code'=>NULL]) : $sitter_order->update(['status'=>'completed']);
            $sitter_order->update(['status'=>'completed','otp_code'=>NULL]);
            $sitter = User::findOrFail($sitter_order->sitter_id);
            $wallet_before = $sitter->wallet;
            $wallet_after = $wallet_before + $order->final_price;
            $this->chargeWallet($order->final_price, $sitter_order->sitter_id);
            if ($sitter_order->pay_type == 'wallet') {

                Wallet::create(['amount' => $order->final_price, 'wallet_before' => $wallet_before, 'wallet_after' => $wallet_after, 'user_id' => $order->sitter_id, 'transferd_by' => $order->client_id, 'order_id' => $order->id]);
            }
            // return true;
    }
}
?>

<?php

namespace App\Traits;

use App\Models\{MainOrder, OrderMonthDate, SitterOrder, User, Wallet};
use Illuminate\Support\Facades\DB;

trait CompleteOrderMonthService
{
    private function totalDates(MainOrder $order, $status)
    {
        $sitter_order = $order->sitter_order;
        $total_price = 0;
        $total_dates = OrderMonthDate::where(['order_month_id' => optional($sitter_order->months)->id, 'status' => $status])->get();
        foreach ($total_dates as $total_date) {
            $start_time = optional($total_date->order_day)->start_time;
            $end_time = optional($total_date->order_day)->end_time;
            $hours = $end_time->diffInHours($start_time);
            $total_price += ($hours * optional($total_date->month)->price_per_hour_for_month);
        }
        // dd($total_price);
        return $total_price;
    }

    public function chargeWalletForProvider(MainOrder $order, User $user, $status)
    {
        $total_price = $this->totalDates($order, $status);

        if ($total_price > 0) {

            $user_wallet_before = $user->wallet;
            $user_wallet_after = $user->wallet + $total_price;
            $user->update(['wallet' => $user_wallet_after]);
            if (optional($order->sitter_order)->pay_type == 'wallet') {
                Wallet::create(['amount' => $total_price, 'wallet_before' => $user_wallet_before, 'wallet_after' => $user_wallet_after, 'user_id' => $order->client_id, 'transferd_by' => $order->sitter_id, 'order_id' => $order->id]);
            }
            // $this->chargeWallet($total_canceled_price, optional($order->sitter_order)->client_id);
        }
    }
}

<?php

namespace App\Traits;

use App\Http\Requests\Api\BabySitter\Order\OTPRequest;
use App\Http\Requests\Api\BabySitter\Order\ResendOTPRequest;
use App\Http\Resources\Api\Notification\SenderResource;
use App\Models\MainOrder;
use App\Models\OrderMonthDate;
use App\Models\SitterOrder;
use App\Models\User;
use App\Models\Wallet;
use App\Notifications\Orders\DeliverChildernNotification;
use App\Notifications\Orders\RecieveChildernNotification;
use Illuminate\Support\Facades\Notification;

trait OTP
{

    use Order;

    public function sendOTP($order_id, $status)
    {
        $order = MainOrder::where('sitter_id', auth('api')->id())->findOrFail($order_id);
        //optional($order->sitter_order)->id
        $service_id = $order->to == 'sitter' ? optional($order->sitter_order)->service_id : optional($order->center_order)->service_id;

        if ($service_id == 1) {  // 1=>hour
            $otp_order = SitterOrder::where('status', $status)->findOrFail(optional($order->sitter_order)->id);
        } else {

            $sitter_order = SitterOrder::where(['status' => 'process', 'service_id' => $service_id])->findOrFail(optional($order->sitter_order)->id);
            // dd($sitter_order);
            $otp_order = $sitter_order->months->month_dates()->where('status', $status)->orderBy('date', 'ASC')->firstOrFail();
        }
        $otp = 1111;
        if (setting('use_sms_service') == 'enable') {
            $otp = mt_rand(1111, 9999); //generate_unique_code(4,'\\App\\Models\\User','verified_code');
        }
        $otp_order->update(['otp_code' => $otp]);
        $this->sendVerifyOTP($order);

        return response()->json(['data' => null, 'status' => 'success', 'message' => trans('api.messages.otp_has_been_sent')]);
    }




    public function checkOtpValidity(OTPRequest $request, $current_status, $updated_status)
    {

        $order = MainOrder::where('sitter_id', auth('api')->id())->findOrFail($request->order_id);
        $service_id = $order->to == 'sitter' ? optional($order->sitter_order)->service_id : optional($order->center_order)->service_id;
        if ($service_id == 1) {
            $sitter_order = SitterOrder::where(['status' => $current_status, 'otp_code' => $request->otp_code, 'main_order_id' => $order->id])->firstOrFail();
        } else {

            $order_for_sitter = SitterOrder::where(['status' => 'process', 'main_order_id' => $order->id])->firstOrFail();

            $sitter_order = $order_for_sitter->months->month_dates()->where(['status' => $current_status, 'otp_code' => $request->otp_code])->orderBy('date', 'ASC')->firstOrFail();
            // dd('ss');
            // dd($sitter_order);
        }

        if (isset($sitter_order) && $sitter_order) {

            if ($updated_status == 'with_the_child') {
                $sitter_order->update(['status' => $updated_status, 'otp_code' => NULL]);
                $fcm_notes = [
                    'title' => ['dashboard.notification.sitter_has_been_recieved_childern_title'],
                    'body' => ['dashboard.notification.sitter_has_been_recieved_childern_body', ['body' => auth('api')->user()->name ?? auth('api')->user()->phone]],
                    'sender_data' => new SenderResource(auth('api')->user())
                ];
                $order->client->notify(new RecieveChildernNotification($order, ['database']));

                $admins = User::whereIn('user_type', ['superadmin', 'admin'])->get();
                Notification::send($admins, new RecieveChildernNotification($order, ['database', 'broadcast']));
                pushFcmNotes($fcm_notes, optional($order->client)->devices);
            } else {
                // $sitter_order->update(['status'=>$updated_status]);
                if ($service_id == 1) {
                    $order->update(['finished_at' => now()]);

                    if ($sitter_order->pay_type == 'wallet') {

                        $sitter = User::findOrFail($sitter_order->sitter_id);
                        $wallet_before = $sitter->wallet;
                        $this->chargeWallet($order->final_price, $sitter_order->sitter_id);
                        Wallet::create(['amount' => $order->final_price, 'wallet_before' => $wallet_before, 'wallet_after' => $sitter->wallet, 'user_id' => $order->sitter_id, 'transferd_by' => $order->client_id, 'order_id' => $order->id]);
                    }
                } else {
                    //    dd($sitter_order);
                    //     $sitter_order_month = $sitter_order->months->month_dates()->where('status','with_the_child')->first();
                    //     dd($sitter_order_month);
                    // $start_time = optional($center_order_month->order_day)->start_time;
                    // $end_time = optional($center_order_month->order_day)->end_time;
                    // $hours = $end_time->diffInHours($start_time);
                    // $price = $hours*optional($center_order->months)->price_per_hour_for_month;
                    // $this->chargeWallet($price,$center_order->client_id);
                    // dd($sitter_order);
                    $sitter_order->update(['status'=>$updated_status,'otp_code'=>NULL]);
                    if (isset($sitter_order) && $sitter_order) {
                        $last_day = OrderMonthDate::where('order_month_id',$order->sitter_order->months->id)->orderBy('date', 'DESC')->firstOrFail();
                        // dd($last_day == $sitter_order);
                    }

                    if ($last_day->id == $sitter_order->id) {

                        $order->sitter_order()->update(['status'=>'completed']);
                        if (optional($order->sitter_order)->pay_type == 'wallet') {
                            $total_canceled_price = 0;
                            $total_completed_price = 0;
                            $canceled_dates = OrderMonthDate::where(['order_month_id' => optional($sitter_order->month)->id, 'status' => 'canceled'])->get();

                            $completed_dates = OrderMonthDate::where(['order_month_id' => optional($sitter_order->month)->id, 'status' => 'completed'])->get();

                            foreach ($canceled_dates as $cancel_date) {
                                $start_time = optional($cancel_date->order_day)->start_time;
                                $end_time = optional($cancel_date->order_day)->end_time;
                                $hours = $end_time->diffInHours($start_time);
                                $total_canceled_price += ($hours * optional($cancel_date->month)->price_per_hour_for_month);
                            }

                            foreach ($completed_dates as $completed_date) {
                                $start_time = optional($completed_date->order_day)->start_time;
                                $end_time = optional($completed_date->order_day)->end_time;
                                $hours = $end_time->diffInHours($start_time);
                                $total_completed_price += ($hours * optional($completed_date->month)->price_per_hour_for_month);
                            }



                            if ($total_canceled_price > 0) {

                                $client = $order->client;
                                $client_wallet_before = $client->wallet;
                                $client_wallet_after = $client->wallet + $total_canceled_price;
                                Wallet::create(['amount' => $total_canceled_price, 'wallet_before' => $client_wallet_before, 'wallet_after' => $client_wallet_after, 'user_id' => $order->client_id, 'transferd_by' => $order->sitter_id, 'order_id' => $order->id]);
                                $this->chargeWallet($total_canceled_price, optional($order->sitter_order)->client_id);
                            }
                            if ($total_completed_price > 0) {

                                $sitter = $order->sitter;
                                $sitter_wallet_before = $sitter->wallet;
                                $sitter_wallet_after = $sitter->wallet + $total_completed_price;
                                Wallet::create(['amount' => $total_completed_price, 'wallet_before' => $sitter_wallet_before, 'wallet_after' => $sitter_wallet_after, 'user_id' => $order->sitter_id, 'transferd_by' => $order->client_id, 'order_id' => $order->id]);
                                $this->chargeWallet($total_completed_price, optional($order->sitter_order)->sitter_id);
                            }

                            //  Wallet::create(['amount'=>$order->final_price,'wallet_before'=>$wallet_before,'wallet_after'=>$sitter->wallet,'user_id'=>$order->sitter_id,'transferd_by'=>$order->client_id,'order_id'=>$order->id]);

                        }
                    }
                }



                $fcm_notes = [
                    'title' => ['dashboard.notification.sitter_has_been_deliver_childern_title'],
                    'body' => ['dashboard.notification.sitter_has_been_deliver_childern_body', ['body' => auth('api')->user()->name ?? auth('api')->user()->phone]],
                    'sender_data' => new SenderResource(auth('api')->user())
                ];
                $order->client->notify(new DeliverChildernNotification($order, ['database']));

                $admins = User::whereIn('user_type', ['superadmin', 'admin'])->get();
                pushFcmNotes($fcm_notes, optional($order->client)->devices);
                Notification::send($admins, new DeliverChildernNotification($order, ['database', 'broadcast']));
            }
            return response()->json(['data' => null, 'status' => 'success', 'message' => trans('api.messages.otp_is_valid')]);
        }
        return response()->json(['data' => null, 'status' => 'fail', 'message' => trans('api.messages.otp_is_not_valid')], 400);
    }
}

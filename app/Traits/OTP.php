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
use Illuminate\Support\Facades\DB;
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




    public function checkOtpValidity(OTPRequest $request, $current_status)
    {
        DB::beginTransaction();

        try {
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

            $sitter_order->update(['otp_code' => NULL]);
        }
        DB::commit();
        return response()->json(['data' => null, 'status' => 'success', 'message' => trans('api.messages.otp_is_valid')]);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['data' => null, 'status' => 'fail', 'message' => trans('api.messages.there_is_an_error_try_again')], 400);
    }

    }
}

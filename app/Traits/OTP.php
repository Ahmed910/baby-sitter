<?php

namespace App\Traits;

use App\Http\Requests\Api\BabySitter\Order\OTPRequest;
use App\Models\MainOrder;
use App\Models\SitterOrder;
use App\Models\User;
use App\Notifications\Orders\DeliverChildernNotification;
use App\Notifications\Orders\RecieveChildernNotification;
use Illuminate\Support\Facades\Notification;

trait OTP
{
    public function sendOTP($order_id, $status)
    {
        $order = MainOrder::where('sitter_id', auth('api')->id())->findOrFail($order_id);

        $sitter_order = SitterOrder::where('status', $status)->findOrFail(optional($order->sitter_order)->id);
        $otp = 1111;
        if (setting('use_sms_service') == 'enable') {
            $otp = mt_rand(1111, 9999); //generate_unique_code(4,'\\App\\Models\\User','verified_code');
        }
        $sitter_order->update(['otp_code' => $otp]);
        $this->sendVerifyOTP($order->sitter);

        return response()->json(['data' => null, 'status' => 'success', 'message' => trans('api.messages.otp_has_been_sent')]);
    }

    public function checkOtpValidity(OTPRequest $request, $current_status, $updated_status)
    {
        $order = MainOrder::where('sitter_id', auth('api')->id())->findOrFail($request->order_id);

        $sitter_order = SitterOrder::where(['status' => $current_status, 'otp_code' => $request->otp_code, 'main_order_id' => $order->id])->first();
        if (isset($sitter_order) && $sitter_order) {
            $sitter_order->update(['status' => $updated_status, 'otp_code' => NULL]);
            if ($updated_status == 'with_the_child') {
                $order->client->notify(new RecieveChildernNotification($order, ['database']));

                $admins = User::whereIn('user_type', ['superadmin', 'admin'])->get();
                Notification::send($admins, new RecieveChildernNotification($order, ['database', 'broadcast']));
            } else {
                $order->client->notify(new DeliverChildernNotification($order, ['database']));

                $admins = User::whereIn('user_type', ['superadmin', 'admin'])->get();
                Notification::send($admins, new DeliverChildernNotification($order, ['database', 'broadcast']));
            }
            return response()->json(['data' => null, 'status' => 'success', 'message' => trans('api.messages.otp_is_valid')]);
        }
        return response()->json(['data' => null, 'status' => 'fail', 'message' => trans('api.messages.otp_is_not_valid')], 400);
    }
}

<?php

namespace App\Traits;

use App\Classes\Statuses;
use App\Http\Requests\Api\BabySitter\Order\{OTPRequest, ResendOTPRequest};
use App\Http\Resources\Api\Notification\SenderResource;
use App\Models\{MainOrder, OrderMonthDate, SitterOrder, User, Wallet};
use App\Notifications\Orders\{DeliverChildernNotification, RecieveChildernNotification};
use Illuminate\Support\Facades\{DB, Notification};


trait OTP
{

    use Order, WithTheChild, CompleteOrderHourService, CompleteOrderMonthService;

    public function sendOTP($order_id, $status)
    {
        $order = MainOrder::where('sitter_id', auth('api')->id())->findOrFail($order_id);
        //optional($order->sitter_order)->id
        $service_id = $order->to == 'sitter' ? optional($order->sitter_order)->service_id : optional($order->center_order)->service_id;

        if ($service_id == Statuses::HOUR_SERVICE) {  // 1=>hour
            $otp_order = SitterOrder::where('status', $status)->findOrFail(optional($order->sitter_order)->id);
        } else {

            $sitter_order = SitterOrder::where(['status' => 'process', 'service_id' => $service_id])->findOrFail(optional($order->sitter_order)->id);
            // dd($sitter_order);
            $otp_order = $sitter_order->months->month_dates()->where('order_month_dates.status', $status)->orderBy('order_month_dates.date', 'ASC')->firstOrFail();
        }
        $otp = 1111;
        if (setting('use_sms_service') == 'enable') {
            $otp = mt_rand(1111, 9999); //generate_unique_code(4,'\\App\\Models\\User','verified_code');
        }
        $otp_order->update(['otp_code' => $otp]);
        $this->sendVerifyOTP($order);

        return response()->json(['data' => ['otp_code' => $otp], 'status' => 'success', 'message' => trans('api.messages.otp_has_been_sent')]);
    }



    public function checkOtpValidityToRecieveChildern(OTPRequest $request)
    {
        $order = MainOrder::where('sitter_id', auth('api')->id())->findOrFail($request->order_id);
        $service_id = $order->to == 'sitter' ? optional($order->sitter_order)->service_id : optional($order->center_order)->service_id;
        if ($service_id == Statuses::HOUR_SERVICE) {
            $sitter_order = SitterOrder::where(['status' => Statuses::WAITING, 'otp_code' => $request->otp_code, 'main_order_id' => $order->id])->firstOrFail();
        } else {
            $order_for_sitter = SitterOrder::where(['status' => Statuses::PROCESS, 'main_order_id' => $order->id])->firstOrFail();
            $sitter_order = $order_for_sitter->months->month_dates()->where(['order_month_dates.status' => Statuses::WAITING, 'order_month_dates.otp_code' => $request->otp_code])->orderBy('order_month_dates.date', 'ASC')->firstOrFail();
        }
        $this->updateOrderStatusToWithTheChild($sitter_order, $order, Statuses::WITHTHECHILD);
        return response()->json(['data' => null, 'status' => 'success', 'message' => trans('api.messages.otp_is_valid')]);
    }

    public function checkOtpValidityToDeliverChildern(OTPRequest $request)
    {
        $order = MainOrder::where('sitter_id', auth('api')->id())->findOrFail($request->order_id);
        $service_id = $order->to == 'sitter' ? optional($order->sitter_order)->service_id : optional($order->center_order)->service_id;

        if ($service_id == Statuses::HOUR_SERVICE) {

            return $this->completeOrderForHourService($order, $request->otp_code);
        } else {
            $order_for_sitter = SitterOrder::where(['status' => Statuses::PROCESS, 'main_order_id' => $order->id])->firstOrFail();
            $sitter_order = $order_for_sitter->months->month_dates()->where(['order_month_dates.status' => Statuses::WITHTHECHILD, 'order_month_dates.otp_code' => $request->otp_code])->orderBy('order_month_dates.date', 'ASC')->first();
            if (!$sitter_order) {
                return response()->json(['data' => null, 'status' => 'fail', 'message' => trans('api.messages.otp_is_not_valid')], 400);
            }
            $last_day = OrderMonthDate::where('order_month_id', $order->sitter_order->months->id)->orderBy('date', 'DESC')->firstOrFail();
            DB::beginTransaction();
            try {
                $sitter_order->update(['status' => Statuses::COMPLETED, 'otp_code' => NULL]);

                if ($last_day->id == $sitter_order->id) {

                    $order->sitter_order()->update(['status' => Statuses::COMPLETED]);
                    $this->chargeWalletForProvider($order, $order->client, Statuses::CANCELED);
                    $this->chargeWalletForProvider($order, $order->sitter, Statuses::COMPLETED);
                }
                DB::commit();

                $fcm_notes = [
                    'title' => trans('dashboard.notification.sitter_has_been_deliver_childern_title'),
                    'body' => trans('dashboard.notification.sitter_has_been_deliver_childern_body').auth('api')->user()->name,
                    'sender_data' => new SenderResource(auth('api')->user())
                ];
                $order->client->notify(new DeliverChildernNotification($order, ['database']));

                $admins = User::whereIn('user_type', ['superadmin', 'admin'])->get();
                pushFcmNotes($fcm_notes, optional($order->client)->devices);
                Notification::send($admins, new DeliverChildernNotification($order, ['database', 'broadcast']));
                return response()->json(['data' => null, 'status' => 'success', 'message' => trans('api.messages.otp_is_valid')]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['data' => null, 'status' => 'fail', 'message' => trans('api.messages.there_is_an_error_try_again')], 400);
            }
        }


    }
}

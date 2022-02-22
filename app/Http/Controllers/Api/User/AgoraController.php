<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Notification\SenderResource;
use App\Models\MainOrder;
use Illuminate\Http\Request;

class AgoraController extends Controller
{

    public function start(Request $request, $order_id)
    {
        \DB::beginTransaction();
        try {
            $order = MainOrder::findOrFail($order_id);
            // if ($order->status == 'accept') {
            //     return response()->json(['status' => 'false', 'message' => trans('app.messages.not_allowed_to_modify'), 'data' => null], 401);
            // }
            $agora_data = [];
            if ($order) {
                $agora_channel_name = 'order_' . $order->id;
                $uid = $order->id;
                $agora_expire_time_in_seconds = (int) (setting('agora_expire_time_in_seconds') ? setting('agora_expire_time_in_seconds') : (3600 * 48));
                $agora_token = generate_agora_token($agora_channel_name, 0, $agora_expire_time_in_seconds);
                $agora_data = [
                    'agora_channel_name' => $agora_channel_name,
                    'agora_expire_time_in_seconds' => $agora_expire_time_in_seconds,
                    'agora_token' => $agora_token,
                ];
            }
            $order->update($agora_data);
            // notification to client
            $data = [
                'key' => "client_start_order",
                'key_type' => "order",
                'key_id' => $order->id,
                'sound' => 'ring.mp3',
                'order_type' => $order->type_order,
                'status' => $order->status,
                'title' => [
                    'ar' => trans('app.notification.title.client_start_order', ['sender_name' => auth('api')->user()->fullname, 'order_number' => $order->id], 'ar'),
                    'en' => trans('app.notification.title.client_start_order', ['sender_name' => auth('api')->user()->fullname, 'order_number' => $order->id], 'en'),
                ],
                'body' => [
                    'ar' => trans('app.notification.body.client_start_order', ['sender_name' => auth('api')->user()->fullname, 'conusltation_number' => $order->id], 'ar'),
                    'en' => trans('app.notification.body.client_start_order', ['sender_name' => auth('api')->user()->fullname, 'conusltation_number' => $order->id], 'en'),
                ],
                'sender_data' => new SenderResource(auth('api')->user()),
                'agora_data' => $agora_data
            ];
            $order->sitter->notify(new ApiNotification($data, ['database', 'fcm']));
            \DB::commit();
            if ($order) {
                $agora_data = [
                    'channel_name' => $agora_channel_name,
                    'expire_time_in_seconds' => $agora_expire_time_in_seconds,
                    'token' => $agora_token,
                ];
                return response()->json(['status' => 'true', 'message' => trans('app.messages.sent_successfully'), 'data' => $agora_data + ['uid' => 0, 'order_id' => $order->id]]);
            }
            return response()->json(['status' => 'true', 'message' => trans('app.messages.sent_successfully'), 'data' => null]);
        } catch (\Exception $e) {
            \DB::rollback();
            dd($e);
            return response()->json(['status' => 'false', 'message' => trans('app.messages.something_went_wrong_please_try_again'), 'data' => null], 401);
        }
    }
}

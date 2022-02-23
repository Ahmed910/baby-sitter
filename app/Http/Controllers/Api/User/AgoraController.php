<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Notification\SenderResource;
use App\Models\MainOrder;
use App\Notifications\Orders\StartMeetingNotification;
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
            if($order->sitter_id == auth('api')->id()){
                $data = [
                    'title'=>trans('dashboard.notification.meeting_has_been_started_title',[],optional($order->client)->current_lang),
                    'body'=> trans('dashboard.notification.meeting_has_been_started_body',[],optional($order->client)->current_lang),
                    'sender_data' => new SenderResource(auth('api')->user()),
                    'agora_data' => $agora_data
                ];
                $order->client->notify(new StartMeetingNotification($order));
                pushFcmNotes($data,optional($order->client)->devices);
            }

            if($order->client_id == auth('api')->id()){
                $data = [
                    'title'=>trans('dashboard.notification.meeting_has_been_started_title',[],optional($order->sitter)->current_lang),
                    'body'=> trans('dashboard.notification.meeting_has_been_started_body',[],optional($order->sitter)->current_lang),
                    'sender_data' => new SenderResource($order->client),
                    'agora_data' => $agora_data
                ];
                $order->sitter->notify(new StartMeetingNotification($order));
                pushFcmNotes($data,optional($order->sitter)->devices);
            }

            \DB::commit();

            if ($order) {
                $agora_data = [
                    'channel_name' => $agora_channel_name,
                    'expire_time_in_seconds' => $agora_expire_time_in_seconds,
                    'token' => $agora_token,
                ];
                return response()->json(['status' => 'success', 'message' => trans('api.messages.sent_successfully'), 'data' => $agora_data + ['uid' => 0, 'order_id' => $order->id]]);
            }
            return response()->json(['status' => 'success', 'message' => trans('api.messages.sent_successfully'), 'data' => null]);
        } catch (\Exception $e) {
            \DB::rollback();
            dd($e);
            return response()->json(['status' => 'fail', 'message' => trans('api.messages.there_is_an_error_try_again'), 'data' => null], 401);
        }
    }
}

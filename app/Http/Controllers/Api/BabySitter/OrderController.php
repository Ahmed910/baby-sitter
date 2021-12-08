<?php

namespace App\Http\Controllers\Api\BabySitter;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BabySitter\Order\OTPRequest;
use App\Http\Resources\Api\BabySitter\Order\OrderResource;
use App\Http\Resources\Api\Client\Order\SingleSitterOrderResource;
use App\Models\MainOrder;
use App\Models\SitterOrder;
use App\Models\User;
use App\Notifications\Orders\AcceptOrderNotification;
use App\Notifications\Orders\CancelOrderNotification;
use App\Notifications\Orders\RejectOrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    public function getOrders()
    {
        $data=[];
        $new_orders = MainOrder::where(['to'=>'sitter','sitter_id'=>auth('api')->id()])->whereHas('sitter_order',function($q){
            $q->where('status','pending');
        })->get();
        $data['new_orders'] = OrderResource::collection($new_orders);
        $active_orders = MainOrder::where(['to'=>'sitter','sitter_id'=>auth('api')->id()])->whereHas('sitter_order',function($q){
            $q->whereIn('status',['waiting','with_the_child']);
        })->get();
        $data['active_orders'] = OrderResource::collection($active_orders);
        $expired_orders = MainOrder::where(['to'=>'sitter','sitter_id'=>auth('api')->id()])->whereHas('sitter_order',function($q){
            $q->whereIn('status',['rejected','completed','canceled']);
        })->get();
        $data['expired_orders'] = OrderResource::collection($expired_orders);
        return response()->json(['data'=>$data,'status'=>'success','message'=>'']);
    }

    public function getOrderDetails($order_id)
    {
        $sitter_order = MainOrder::where('sitter_id',auth('api')->id())->findOrFail($order_id)->sitter_order;
        if (isset($sitter_order) && $sitter_order) {
            return (new SingleSitterOrderResource($sitter_order))->additional(['status'=>'success','message'=>'']);
        }
        return response()->json(['data'=>null,'status'=>'fail','message'=>trans('api.messages.id_not_found')],404);
    }

    public function acceptOrder($order_id)
    {
        $order = MainOrder::where('sitter_id',auth('api')->id())->findOrFail($order_id);

        $sitter_order = SitterOrder::where('status','pending')->findOrFail(optional($order->sitter_order)->id);
        $sitter_order->update(['status'=>'waiting']);
        $order->client->notify(new AcceptOrderNotification($order,['database']));

        $admins = User::whereIn('user_type',['superadmin','admin'])->get();
        Notification::send($admins, new AcceptOrderNotification($order,['database','broadcast']));
        return response()->json(['data' => null, 'status' => 'success', 'message' => trans('api.messages.order_has_been_accepted')]);
    }

    public function cancelOrder($order_id)
    {
        $main_order = MainOrder::where('sitter_id',auth('api')->id())->findOrFail($order_id);

           // $main_order->sitter_order()->whereIn('status',['pending','waiting'])->update(['status'=>'canceled']);
            $sitter_order=SitterOrder::where('status','waiting')->findOrFail($main_order->sitter_order->id);
            $sitter_order->update(['status'=>'canceled']);

            $main_order->client->notify(new CancelOrderNotification($main_order,['database']));

            $admins = User::whereIn('user_type',['superadmin','admin'])->get();
            Notification::send($admins, new CancelOrderNotification($main_order,['database','broadcast']));
            return response()->json(['data' => null, 'status' => 'success', 'message' => trans('api.messages.order_canceled')]);
    }

    public function rejectOrder($order_id)
    {
        $order = MainOrder::where('sitter_id',auth('api')->id())->findOrFail($order_id);

        $sitter_order = SitterOrder::where('status','pending')->findOrFail(optional($order->sitter_order)->id);
        $sitter_order->update(['status'=>'rejected']);
        $order->client->notify(new RejectOrderNotification($order,['database']));

        $admins = User::whereIn('user_type',['superadmin','admin'])->get();
        Notification::send($admins, new RejectOrderNotification($order,['database','broadcast']));
        return response()->json(['data' => null, 'status' => 'success', 'message' => trans('api.messages.order_has_been_rejected')]);
    }

    public function sendOTP($order_id)
    {
        $order = MainOrder::where('sitter_id',auth('api')->id())->findOrFail($order_id);

        $sitter_order = SitterOrder::where('status','waiting')->findOrFail(optional($order->sitter_order)->id);
        $otp = 1111;
        if (setting('use_sms_service') == 'enable') {
            $otp = mt_rand(1111,9999);//generate_unique_code(4,'\\App\\Models\\User','verified_code');
        }
        $sitter_order->update(['otp_code'=>$otp]);
        $this->sendVerifyOTP($order->sitter);

    return response()->json(['data'=>null,'status'=>'success','message'=>trans('api.messages.otp_has_been_sent')]);
    }
    public function checkOtpValidity(OTPRequest $request)
    {
        $order = MainOrder::where('sitter_id',auth('api')->id())->findOrFail($request->order_id);

        $sitter_order = SitterOrder::where(['status'=>'waiting','otp_code'=>$request->otp_code])->first();
        if(isset($sitter_order) && $sitter_order){
            $sitter_order->update(['status'=>'with_the_child','otp_code'=>NULL]);
           return response()->json(['data'=>null,'status'=>'success','message'=>trans('api.messages.otp_is_valid')]);
        }
        return response()->json(['data'=>null,'status'=>'fail','message'=>trans('api.messages.otp_is_not_valid')],400);

    }

    protected function sendVerifyOTP($user)
    {
        if (setting('use_sms_service') == 'enable') {
            $message = trans('api.auth.verified_code_is',['code' => $user->verified_code]);
            $response = send_sms($user->phone, $message);

            if (setting('sms_provider') == 'hisms' && $response['response'] != 3) {
                $user->forceDelete();
                $sms_response = $response['result'];
                return response()->json(['status' => 'fail','data'=> null ,'message'=> "لم يتم حفظ رجاء التحقق من البيانات ( ".$sms_response." )" ], 422);
            }
        }
    }
}

<?php

namespace App\Http\Controllers\Api\BabySitter;

use App\Classes\OrderStatuses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BabySitter\Order\OTPRequest;
use App\Http\Resources\Api\Client\Order\OrderResource;
use App\Http\Resources\Api\Client\Order\SingleOrderResource;
use App\Http\Resources\Api\Client\Order\SingleSitterOrderResource;
use App\Models\MainOrder;
use App\Models\SitterOrder;
use App\Models\User;
use App\Notifications\Orders\AcceptOrderNotification;
use App\Notifications\Orders\CancelOrderNotification;
use App\Notifications\Orders\RejectOrderNotification;
use App\Traits\OTP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    use OTP;
    public $order;
    public function __construct(OrderStatuses $order)
    {
       $this->order = $order;
    }


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

        return $this->order->getDetailsForOrder($order_id,'sitter_id');
    }

    public function acceptOrder($order_id)
    {
        $order = MainOrder::where('sitter_id',auth('api')->id())->findOrFail($order_id);

        $sitter_order = SitterOrder::where('status','pending')->findOrFail(optional($order->sitter_order)->id);
        $sitter_order->update(['status'=>'waiting']);
        $order->refresh();
        $order->client->notify(new AcceptOrderNotification($order,['database']));

        $admins = User::whereIn('user_type',['superadmin','admin'])->get();
        Notification::send($admins, new AcceptOrderNotification($order,['database','broadcast']));
        return (new SingleOrderResource($order))->additional(['status'=>'success','message'=>'']);
    }

    public function cancelOrder($order_id)
    {
        $main_order = MainOrder::where('sitter_id',auth('api')->id())->findOrFail($order_id);

           // $main_order->sitter_order()->whereIn('status',['pending','waiting'])->update(['status'=>'canceled']);
            $sitter_order=SitterOrder::where('status','waiting')->findOrFail($main_order->sitter_order->id);
            $sitter_order->update(['status'=>'canceled']);
            $main_order->refresh();
            $main_order->client->notify(new CancelOrderNotification($main_order,['database']));

            $admins = User::whereIn('user_type',['superadmin','admin'])->get();
            Notification::send($admins, new CancelOrderNotification($main_order,['database','broadcast']));
            return (new SingleOrderResource($main_order))->additional(['status'=>'success','message'=>'']);
    }

    public function rejectOrder($order_id)
    {
        $order = MainOrder::where('sitter_id',auth('api')->id())->findOrFail($order_id);

        $sitter_order = SitterOrder::where('status','pending')->findOrFail(optional($order->sitter_order)->id);
        $sitter_order->update(['status'=>'rejected']);
        $order->refresh();
        $order->client->notify(new RejectOrderNotification($order,['database']));

        $admins = User::whereIn('user_type',['superadmin','admin'])->get();
        Notification::send($admins, new RejectOrderNotification($order,['database','broadcast']));
        return (new SingleOrderResource($order))->additional(['status'=>'success','message'=>'']);
    }

    public function sendOTPToReceiveChildern($order_id)
    {
        return $this->sendOTP($order_id,'waiting');
    }
    public function checkOtpValidityAndRecieveChildern(OTPRequest $request)
    {
        return $this->checkOtpValidity($request,'waiting','with_the_child');
    }

    public function sendOTPToDeliverChildern($order_id)
    {
        return $this->sendOTP($order_id,'with_the_child');
    }

    public function checkOtpValidityAndDeliverChildern(OTPRequest $request)
    {
        return $this->checkOtpValidity($request,'with_the_child','completed');
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

<?php

namespace App\Http\Controllers\Api\BabySitter;

use App\Classes\OrderStatuses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BabySitter\Order\OTPRequest;
use App\Http\Requests\Api\BabySitter\Order\ResendOTPRequest;
use App\Http\Resources\Api\Client\Order\NewOrderResource;
use App\Http\Resources\Api\Client\Order\OrderResource;
use App\Http\Resources\Api\Client\Order\SingleOrderResource;
use App\Http\Resources\Api\Client\Order\SingleSitterOrderResource;
use App\Http\Resources\Api\Notification\SenderResource;
use App\Models\MainOrder;
use App\Models\SitterOrder;
use App\Models\User;
use App\Notifications\Orders\AcceptOrderNotification;
use App\Notifications\Orders\CancelOrderNotification;
use App\Notifications\Orders\RejectOrderNotification;
use App\Traits\Order;
use App\Traits\OTP;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    use OTP, Order;
    public $order;
    public function __construct(OrderStatuses $order)
    {
        $this->order = $order;
    }


    public function getNewOrders()
    {

        $orders = MainOrder::where(['to' => 'sitter', 'sitter_id' => auth('api')->id()])->whereHas('sitter_order', function ($q){
            $q->where('status', 'pending');
        })->get();

        return NewOrderResource::collection($orders)->additional(['status' => 'success', 'message' => '']);
    }

    public function getActiveAndExpiredOrders()
    {
        $data =[];
        $active_orders = MainOrder::where(['to' => 'sitter', 'sitter_id' => auth('api')->id()])->whereHas('sitter_order',function($q){
            $q->whereIn('status', ['waiting', 'with_the_child']);
        })->get();
        $data['active_orders'] = NewOrderResource::collection($active_orders);
        $expired_orders = MainOrder::where(['to' => 'sitter', 'sitter_id' => auth('api')->id()])->whereHas('sitter_order',function($q){
            $q->whereIn('status', ['rejected', 'completed', 'canceled']);
        })->get();
        $data['expired_orders'] = NewOrderResource::collection($expired_orders);
        return response()->json(['data'=>$data,'status'=>'success','message'=>'']);

    }

    public function getOrderDetails($order_id)
    {

        return $this->order->getDetailsForOrder($order_id, 'sitter_id');
    }

    public function acceptOrder($order_id)
    {
        $order = MainOrder::where('sitter_id', auth('api')->id())->findOrFail($order_id);

        $sitter_order = SitterOrder::where('status', 'pending')->findOrFail(optional($order->sitter_order)->id);
        $sitter_order->update(['status' => 'waiting']);
        $order->refresh();
        $fcm_notes = [
            'title' => ['dashboard.notification.order_has_been_accepted_title'],
            'body' => ['dashboard.notification.order_has_been_accepted_body', ['body' => auth('api')->user()->name ?? auth('api')->user()->phone]],
            'sender_data' => new SenderResource(auth('api')->user())
        ];
        $order->client->notify(new AcceptOrderNotification($order, ['database']));

        $admins = User::whereIn('user_type', ['superadmin', 'admin'])->get();
        Notification::send($admins, new AcceptOrderNotification($order, ['database', 'broadcast']));
        pushFcmNotes($fcm_notes, optional($order->client)->devices);
        return (new SingleOrderResource($order))->additional(['status' => 'success', 'message' => '']);
    }

    public function cancelOrder($order_id)
    {
        // dd(auth('api')->user());
        $main_order = MainOrder::where('sitter_id', auth('api')->id())->findOrFail($order_id);

        // $main_order->sitter_order()->whereIn('status',['pending','waiting'])->update(['status'=>'canceled']);
        $sitter_order = SitterOrder::where('status', 'waiting')->findOrFail($main_order->sitter_order->id);
        if($sitter_order->status == 'waiting'){
            $sitter_order_period = optional($sitter_order->service)->service_type =='hour'
            ? $sitter_order->hours()->whereBetween('date',[Carbon::now(),Carbon::now()->addDay()])->first()
            : $sitter_order->months()->whereBetween('start_date',[Carbon::now(),Carbon::now()->addDay()])->first();
        }
        // dd(Carbon::now()->addDay());

        if(isset($sitter_order_period) && $sitter_order_period)
        {
            return response()->json(['data'=>null,'status'=>'fail','message'=>__('api.messages.cannot_cancel_order_before_start_by_24_hour')],400);
        }
        $sitter_order->update(['status' => 'canceled']);
        if ($sitter_order->pay_type == 'wallet') {
            $this->chargeWallet($main_order->price_after_offer, $sitter_order->client_id);
        }
        $main_order->refresh();
        $fcm_notes =  [
            'title' => ['dashboard.notification.client_cancel_order_title'],
            'body' => ['dashboard.notification.client_cancel_order_body', ['body' => auth('api')->user()->name ?? auth('api')->user()->phone]],
            'sender_data' => new SenderResource(auth('api')->user())
        ];
        $main_order->client->notify(new CancelOrderNotification($main_order, ['database']));

        $admins = User::whereIn('user_type', ['superadmin', 'admin'])->get();
        Notification::send($admins, new CancelOrderNotification($main_order, ['database', 'broadcast']));
        pushFcmNotes($fcm_notes, optional($main_order->client)->devices);
        return (new SingleOrderResource($main_order))->additional(['status' => 'success', 'message' => '']);
    }

    public function rejectOrder($order_id)
    {
        $order = MainOrder::where('sitter_id', auth('api')->id())->findOrFail($order_id);

        $sitter_order = SitterOrder::where('status', 'pending')->findOrFail(optional($order->sitter_order)->id);
        $sitter_order->update(['status' => 'rejected']);
        if ($sitter_order->pay_type == 'wallet') {
            $this->chargeWallet($order->price_after_offer, $sitter_order->client_id);
        }
        $order->refresh();
        $fcm_notes = [
            'title' => ['dashboard.notification.order_has_been_rejected_title'],
            'body' => ['dashboard.notification.order_has_been_rejected_body', ['body' => auth('api')->user()->name ?? auth('api')->user()->phone]],
            'sender_data' => new SenderResource(auth('api')->user())
        ];
        $order->client->notify(new RejectOrderNotification($order, ['database']));

        $admins = User::whereIn('user_type', ['superadmin', 'admin'])->get();
        Notification::send($admins, new RejectOrderNotification($order, ['database', 'broadcast']));
        pushFcmNotes($fcm_notes, optional($order->client)->devices);
        return (new SingleOrderResource($order))->additional(['status' => 'success', 'message' => '']);
    }

    public function sendOTPToReceiveChildern($order_id)
    {
        return $this->sendOTP($order_id, 'waiting');
    }
    public function checkOtpValidityAndRecieveChildern(OTPRequest $request)
    {
        return $this->checkOtpValidity($request, 'waiting', 'with_the_child');
    }

    public function sendOTPToDeliverChildern($order_id)
    {
        return $this->sendOTP($order_id, 'with_the_child');
    }

    public function resendOTP(ResendOTPRequest $request)
    {
        $order = MainOrder::where('sitter_id', auth('api')->id())->findOrFail($request->order_id);
        $sitter_order = SitterOrder::findOrFail(optional($order->sitter_order)->id);
        $otp = 1111;
        if (setting('use_sms_service') == 'enable') {
            $otp = mt_rand(1111, 9999); //generate_unique_code(4,'\\App\\Models\\User','verified_code');
        }
        $sitter_order->update(['otp_code' => $otp]);
        $this->sendVerifyOTP($order);

        return response()->json(['data' => null, 'status' => 'success', 'message' => trans('api.messages.otp_has_been_sent')]);
    }

    public function checkOtpValidityAndDeliverChildern(OTPRequest $request)
    {
        return $this->checkOtpValidity($request, 'with_the_child', 'completed');
    }

    protected function sendVerifyOTP($order)
    {
        if (setting('use_sms_service') == 'enable') {
            $message = trans('api.auth.otp_code_is', ['otp' => $order->otp]);
            $response = send_sms(optional($order->sitter)->phone, $message);

            if (setting('sms_provider') == 'hisms' && $response['response'] != 3) {
                // $user->forceDelete();
                $sms_response = $response['result'];
                return response()->json(['status' => 'fail', 'data' => null, 'message' => "لم يتم حفظ رجاء التحقق من البيانات ( " . $sms_response . " )"], 422);
            }
        }
    }
}

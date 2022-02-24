<?php

namespace App\Http\Controllers\Api\BabySitter;

use App\Classes\{OrderStatuses,Statuses};
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BabySitter\Order\{OTPRequest,ResendOTPRequest};
use App\Http\Resources\Api\Client\Order\{NewOrderResource,SingleOrderResource};
use App\Http\Resources\Api\Notification\SenderResource;
use App\Models\{MainOrder, OrderMonthDate, SitterOrder,User};
use App\Notifications\Orders\{AcceptOrderNotification,CancelOrderNotification,RejectOrderNotification};
use App\Traits\{Order,OTP};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB,Notification};


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

        $orders = MainOrder::where(['to' => 'sitter', 'sitter_id' => auth('api')->id()])->whereHas('sitter_order', function ($q) {
            $q->where('status', 'pending');
        })->get();

        return NewOrderResource::collection($orders)->additional(['status' => 'success', 'message' => '']);
    }

    public function getActiveAndExpiredOrders()
    {
        $data = [];
        $active_orders = MainOrder::where(['to' => 'sitter', 'sitter_id' => auth('api')->id()])->whereHas('sitter_order', function ($q) {
            $q->whereIn('status', ['waiting','process', 'with_the_child']);
        })->get();
        $data['active_orders'] = NewOrderResource::collection($active_orders);
        $expired_orders = MainOrder::where(['to' => 'sitter', 'sitter_id' => auth('api')->id()])->whereHas('sitter_order', function ($q) {
            $q->whereIn('status', ['rejected', 'completed', 'canceled']);
        })->get();
        $data['expired_orders'] = NewOrderResource::collection($expired_orders);
        return response()->json(['data' => $data, 'status' => 'success', 'message' => '']);
    }

    public function getOrderDetails($order_id)
    {

        return $this->order->getDetailsForOrder($order_id, 'sitter_id');
    }

    public function acceptOrder($order_id)
    {
        $order = MainOrder::where('sitter_id', auth('api')->id())->findOrFail($order_id);

        $sitter_order = SitterOrder::where('status', 'pending')->findOrFail(optional($order->sitter_order)->id);
        if (optional($sitter_order->service)->service_type == 'hour') {
            $sitter_order->update(['status' => 'waiting']);
        } else {
            $sitter_order->update(['status' => 'process']);
        }
        $order->refresh();
        $fcm_notes = [
            'title' => trans('dashboard.notification.order_has_been_accepted_title',[],$order->client->current_lang),
            'body' => trans('dashboard.notification.order_has_been_accepted_body',[],$order->client->current_lang).auth('api')->user()->name,
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

        if(optional($main_order->sitter_order)->service_id == Statuses::MONTH_SERVICE){
            $order_for_sitter = SitterOrder::where(['status' => Statuses::PROCESS, 'main_order_id' => $main_order->id])->firstOrFail();
            // $order = $order_for_sitter->months->month_dates()->where(['order_month_dates.status' => Statuses::WAITING])->orderBy('order_month_dates.date', 'ASC')->first();
            $order = OrderMonthDate::where(['status' => Statuses::WAITING,'order_month_id'=>$order_for_sitter->months->id])->orderBy('date', 'ASC')->firstOrFail();
            $last_day = OrderMonthDate::where('order_month_id', $order_for_sitter->months->id)->orderBy('date', 'DESC')->first();
        }
        if(optional($main_order->sitter_order)->service_id == Statuses::HOUR_SERVICE){
            $order = SitterOrder::whereIn('status', ['pending', 'waiting'])->findOrFail($main_order->sitter_order->id);
        }


        DB::beginTransaction();

        try {
            $service_id = optional($main_order->sitter_order)->service_id ;
            if($service_id == Statuses::HOUR_SERVICE){
                $order->update(['status' => 'canceled']);
                $this->chargeWallet($main_order->price_after_offer, $main_order->client_id);
            }else{

                $order->update(['status' => 'canceled']);
                if($last_day->id == $order->id){
                    $order_for_sitter->update(['status' => Statuses::COMPLETED]);
                    $this->chargeWalletForProvider($main_order, $main_order->client, Statuses::CANCELED,$main_order->sitter_order);
                    $this->chargeWalletForProvider($main_order, $main_order->sitter, Statuses::COMPLETED,$main_order->sitter_order);
                }
            }
            DB::commit();
            $main_order->refresh();
            $fcm_notes =  [
                'title' => trans('dashboard.notification.client_cancel_order_title',[],$main_order->client->current_lang),
                'body' => trans('dashboard.notification.client_cancel_order_body',[],$main_order->client->current_lang).auth('api')->user()->name,
                'sender_data' => new SenderResource(auth('api')->user())
            ];
            $main_order->client->notify(new CancelOrderNotification($main_order, ['database']));

            $admins = User::whereIn('user_type', ['superadmin', 'admin'])->get();
            Notification::send($admins, new CancelOrderNotification($main_order, ['database', 'broadcast']));
            pushFcmNotes($fcm_notes, optional($main_order->client)->devices);
            return (new SingleOrderResource($main_order))->additional(['status' => 'success', 'message' => '']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['data' => null, 'status' => 'fail', 'message' => trans('api.messages.there_is_an_error_try_again')], 400);
        }
    }

    public function rejectOrder($order_id)
    {
        $order = MainOrder::where('sitter_id', auth('api')->id())->findOrFail($order_id);

        $sitter_order = SitterOrder::where('status', 'pending')->findOrFail(optional($order->sitter_order)->id);
        DB::beginTransaction();

        try {
            $sitter_order->update(['status' => 'rejected']);
            // if ($sitter_order->pay_type == 'wallet') {
            $this->chargeWallet($order->price_after_offer, $sitter_order->client_id);
            // }
            DB::commit();
            $sitter_order->refresh();
            $order->refresh();
            $fcm_notes = [
                'title' => trans('dashboard.notification.order_has_been_rejected_title', [], $order->client->current_lang),
                'body' => trans('dashboard.notification.order_has_been_rejected_body', [], $order->client->current_lang).auth('api')->name,
                'sender_data' => new SenderResource(auth('api')->user())
            ];
            $order->client->notify(new RejectOrderNotification($order, ['database']));

            $admins = User::whereIn('user_type', ['superadmin', 'admin'])->get();
            Notification::send($admins, new RejectOrderNotification($order, ['database', 'broadcast']));
            pushFcmNotes($fcm_notes, optional($order->client)->devices);
            return (new SingleOrderResource($order))->additional(['status' => 'success', 'message' => '']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['data' => null, 'status' => 'fail', 'message' => trans('api.messages.there_is_an_error_try_again')], 400);
        }
    }

    public function sendOTPToReceiveChildern($order_id)
    {

        return $this->sendOTP($order_id, Statuses::WAITING);
    }
    public function checkOtpValidityAndRecieveChildern(OTPRequest $request)
    {
        return $this->checkOtpValidityToRecieveChildern($request);
        // return $this->checkOtpValidity($request, Statuses::WAITING,Statuses::WITHTHECHILD);
    }

    public function sendOTPToDeliverChildern($order_id)
    {
        return $this->sendOTP($order_id, Statuses::WITHTHECHILD);
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
        return $this->checkOtpValidityToDeliverChildern($request);
        // return $this->checkOtpValidity($request, 'with_the_child','completed');
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

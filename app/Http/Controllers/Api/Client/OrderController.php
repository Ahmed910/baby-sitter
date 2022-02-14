<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BabySitter\OrderSitterRequest;
use App\Http\Requests\Api\ChildCenter\OrderCenterRequest;
use App\Http\Resources\Api\Client\Order\MonthDaysInOrderResource;
use App\Http\Resources\Api\Client\Order\NewOrderResource;
use App\Http\Resources\Api\Client\Order\OrderResource;
use App\Http\Resources\Api\Client\Order\SingleCenterResource;
use App\Http\Resources\Api\Client\Order\SingleOrderResource;
use App\Http\Resources\Api\Client\Order\SingleSitterOrderResource;
use App\Http\Resources\Api\Notification\SenderResource;
use App\Models\CenterOrder;
use App\Models\MainOrder;
use App\Models\SitterOrder;
use App\Models\User;
use App\Notifications\Orders\CancelOrderNotification;
use App\Traits\Order;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    use Order;
    public function createOrderForSitter(OrderSitterRequest $request)
    {
        return $this->SitterOrder($request);
    }

    public function createOrderForCenter(OrderCenterRequest $request)
    {
        return $this->CenterOrder($request);
    }

    public function getOrders(Request $request)
    {


        $orders = MainOrder::where('client_id', auth('api')->id())->when(isset($request->order_type), function ($q) use ($request) {
            if ($request->order_type == 'current') {
                $q->whereHas('sitter_order', function ($q) {
                    $q->whereIn('status', ['pending', 'waiting', 'with_the_child', 'process']);
                })->orWhereHas('center_order', function ($q) {
                    $q->whereIn('status', ['pending', 'waiting', 'active', 'process']);
                });
            } elseif ($request->order_type == 'previous') {
                $q->whereHas('sitter_order', function ($q) {
                    $q->whereIn('status', ['rejected', 'canceled', 'completed']);
                })->orWhereHas('center_order', function ($q) {
                    $q->whereIn('status', ['rejected', 'canceled', 'completed']);
                });
            }
        })->get();
        return NewOrderResource::collection($orders)->additional(['status' => 'success', 'message' => '']);
        // return response()->json(['data' => $data, 'status' => 'success', 'message' => '']);
    }

    public function getOrderDetails($order_id)
    {
        $order = MainOrder::where('client_id', auth('api')->id())->findOrFail($order_id);

        return (new SingleOrderResource($order))->additional(['status' => 'success', 'message' => '']);
    }

    public function getDaysInMonthServiceForOrder($order_id)
    {

        $main_order = MainOrder::where('client_id', auth('api')->id())->findOrFail($order_id);

        // dd($main_order);
        // $order = $main_order->to == 'sitter' ? SitterOrder::where(['status'=>'with_the_child','service_id'=>2])->firstOrFail():CenterOrder::where(['status'=>'active','service_id'=>2])->firstOrFail();
        if ($main_order->to == 'sitter') {

            $sitter_order = SitterOrder::where(['main_order_id' => $order_id, 'service_id' => 2])->firstOrFail();
            // dd($sitter_order->months);
            $days = $sitter_order->months->month_dates;
            //    dd($sitter_order->months);
        } elseif ($main_order->to == 'center') {

            $center_order = CenterOrder::where(['main_order_id' => $order_id, 'status' => 'active', 'service_id' => 2])->firstOrFail();
            $days = $center_order->months->month_dates;
            // $days = $main_order->center_order->months->month_days;

        }
        return MonthDaysInOrderResource::collection($days)->additional(['status' => 'success', 'message' => '']);
    }


    public function cancelOrder($order_id)
    {

        $main_order = MainOrder::where('client_id', auth('api')->id())->findOrFail($order_id);
        DB::beginTransaction();

        try {
            if ($main_order->to == 'sitter') {
                // $main_order->sitter_order()->whereIn('status',['pending','waiting'])->update(['status'=>'canceled']);
                $sitter_order = SitterOrder::whereIn('status', ['pending', 'waiting'])->findOrFail($main_order->sitter_order->id);
                if (in_array($sitter_order->status, ['pending', 'waiting'])) {
                    $sitter_order_period = optional($sitter_order->service)->service_type == 'hour'
                        ? $sitter_order->hours()->whereBetween('date', [Carbon::now(), Carbon::now()->addDay()])->first()
                        : $sitter_order->months()->whereBetween('start_date', [Carbon::now(), Carbon::now()->addDay()])->first();
                }
                // dd(Carbon::now()->addDay());

                if (isset($sitter_order_period) && $sitter_order_period) {
                    return response()->json(['data' => null, 'status' => 'fail', 'message' => __('api.messages.cannot_cancel_order_before_start_by_24_hour')], 400);
                }
                //if($sitter_order->status == 'pending' && optional($sitter_order->service)->service_type =='hour' &&  optional($sitter_order->hours)->date )
                $sitter_order->update(['status' => 'canceled']);

                // if ($sitter_order->pay_type == 'wallet') {

                $this->chargeWallet($main_order->price_after_offer, $sitter_order->client_id);
                // }
                $user = $main_order->sitter;
            } else {
                //$main_order->center_order()->whereIn('status',['pending','waiting'])->update(['status'=>'canceled']);
                $center_order = CenterOrder::whereIn('status', ['pending', 'waiting'])->findOrFail($main_order->center_order->id);
                if (in_array($center_order->status, ['pending', 'waiting'])) {
                    $center_order_period = optional($center_order->service)->service_type == 'hour'
                        ? $center_order->hours()->whereBetween('date', [Carbon::now(), Carbon::now()->addDay()])->first()
                        : $center_order->months()->whereBetween('start_date', [Carbon::now(), Carbon::now()->addDay()])->first();
                }

                if (isset($center_order_period) && $center_order_period) {
                    return response()->json(['data' => null, 'status' => 'fail', 'message' => __('api.messages.cannot_cancel_order_before_start_by_24_hour')], 400);
                }
                $center_order->update(['status' => 'canceled']);

                // if ($center_order->pay_type == 'wallet') {
                $this->chargeWallet($main_order->price_after_offer, $center_order->client_id);
                // }
                $user = $main_order->center;
            }

            DB::commit();
            $main_order->refresh();
            $fcm_notes =  [
                'title' => ['dashboard.notification.client_cancel_order_title'],
                'body' => ['dashboard.notification.client_cancel_order_body', ['body' => auth('api')->user()->name ?? auth('api')->user()->phone]],
                'sender_data' => new SenderResource(auth('api')->user())
            ];

            $user->notify(new CancelOrderNotification($main_order, ['database']));

            $admins = User::whereIn('user_type', ['superadmin', 'admin'])->get();
            Notification::send($admins, new CancelOrderNotification($main_order, ['database', 'broadcast']));
            pushFcmNotes($fcm_notes, $user->devices);
            return (new SingleOrderResource($main_order))->additional(['status' => 'success', 'message' => '']);
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
        }
    }
}

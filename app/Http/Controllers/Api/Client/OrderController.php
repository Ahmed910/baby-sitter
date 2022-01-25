<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BabySitter\OrderSitterRequest;
use App\Http\Requests\Api\ChildCenter\OrderCenterRequest;
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
use Illuminate\Http\Request;
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
        // $data = [];
        // $current_orders = MainOrder::where('client_id', auth('api')->id())->whereHas('sitter_order', function ($q) {
        //     $q->whereIn('status', ['pending', 'waiting', 'with_the_child']);
        // })->orWhereHas('center_order', function ($q) {
        //     $q->whereIn('status', ['pending', 'waiting', 'active']);
        // })->get();
        // $data['current_orders'] = OrderResource::collection($current_orders);
        // $previous_orders = MainOrder::where('client_id', auth('api')->id())->whereHas('sitter_order', function ($q) {
        //     $q->whereIn('status', ['rejected', 'canceled', 'completed']);
        // })->orWhereHas('center_order', function ($q) {
        //     $q->whereIn('status', ['rejected', 'canceled', 'completed']);
        // })->get();
        // $data['previous_orders'] = OrderResource::collection($previous_orders);


        $orders = MainOrder::where('client_id', auth('api')->id())->when(isset($request->order_type), function ($q) use ($request) {
            if($request->order_type == 'current'){
                $q->whereHas('sitter_order', function ($q) {
                    $q->whereIn('status', ['pending', 'waiting', 'with_the_child']);
                })->orWhereHas('center_order', function ($q) {
                    $q->whereIn('status', ['pending', 'waiting', 'active']);
                });
            }elseif($request->order_type == 'previous'){
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


    public function cancelOrder($order_id)
    {
        $main_order = MainOrder::where('client_id', auth('api')->id())->findOrFail($order_id);
        if ($main_order->to == 'sitter') {
            // $main_order->sitter_order()->whereIn('status',['pending','waiting'])->update(['status'=>'canceled']);
            $sitter_order = SitterOrder::whereIn('status', ['pending', 'waiting'])->findOrFail($main_order->sitter_order->id);
            $sitter_order->update(['status' => 'canceled']);
            if ($sitter_order->pay_type == 'wallet') {

                $this->chargeWallet($main_order->price_after_offer, $sitter_order->client_id);
            }
            $user = $main_order->sitter;
        } else {
            //$main_order->center_order()->whereIn('status',['pending','waiting'])->update(['status'=>'canceled']);
            $center_order = CenterOrder::whereIn('status', ['pending', 'waiting'])->findOrFail($main_order->center_order->id);
            $center_order->update(['status' => 'canceled']);
            if ($center_order->pay_type == 'wallet') {
                $this->chargeWallet($main_order->price_after_offer, $center_order->client_id);
            }
            $user = $main_order->center;
        }

        $fcm_notes =  [
            'title'=>['dashboard.notification.client_cancel_order_title'],
            'body'=> ['dashboard.notification.client_cancel_order_body',['body' => auth('api')->user()->name ?? auth('api')->user()->phone]],
            'sender_data' => new SenderResource(auth('api')->user())
        ];

        $user->notify(new CancelOrderNotification($main_order, ['database']));

        $admins = User::whereIn('user_type', ['superadmin', 'admin'])->get();
        Notification::send($admins, new CancelOrderNotification($main_order, ['database', 'broadcast']));
        pushFcmNotes($fcm_notes,$user->devices);
        return response()->json(['data' => null, 'status' => 'success', 'message' => trans('api.messages.order_canceled')]);
    }
}

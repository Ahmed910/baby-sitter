<?php

namespace App\Http\Controllers\Api\Client;

use App\Traits\{CompleteOrderHourService, CompleteOrderMonthService, Order};
use App\Classes\Statuses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BabySitter\OrderSitterRequest;
use App\Http\Requests\Api\ChildCenter\OrderCenterRequest;
use App\Http\Resources\Api\Client\Order\{MonthDaysInOrderResource, NewOrderResource, SingleOrderResource};
use App\Http\Resources\Api\Notification\SenderResource;
use App\Models\{CenterOrder, MainOrder, OrderMonthDate, SitterOrder, User, Wallet};
use App\Notifications\Orders\{CancelOrderNotification, DeliverChildernNotification, RecieveChildernNotification};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Notification};

class OrderController extends Controller
{
    use Order, CompleteOrderHourService, CompleteOrderMonthService;

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
        // dd(now()->format('Y-m-d H:i:s'),now()->addHours(12)->format('Y-m-d H:i:s'));
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

        $sitter_order =$main_order->to == 'sitter' ? SitterOrder::whereIn('status', ['pending', 'waiting'])->findOrFail($main_order->sitter_order->id) : CenterOrder::whereIn('status', ['pending', 'waiting'])->findOrFail($main_order->center_order->id);
        DB::beginTransaction();

        try {

                // $main_order->sitter_order()->whereIn('status',['pending','waiting'])->update(['status'=>'canceled']);

                //if($sitter_order->status == 'pending' && optional($sitter_order->service)->service_type =='hour' &&  optional($sitter_order->hours)->date )
                $sitter_order->update(['status' => 'canceled']);

                // if ($sitter_order->pay_type == 'wallet') {

                $this->chargeWallet($main_order->price_after_offer, $sitter_order->client_id);
                // }
                $user = $main_order->to =='sitter' ? $main_order->sitter : $main_order->center;


            DB::commit();
            $main_order->refresh();
            $fcm_notes =  [
                'title' => trans('dashboard.notification.client_cancel_order_title',[],$main_order->to == 'sitter'?$main_order->sitter->current_lang:$main_order->center->current_lang),
                'body' => trans('dashboard.notification.client_cancel_order_body',[],$main_order->to == 'sitter'?$main_order->sitter->current_lang:$main_order->center->current_lang).auth('api')->user()->name,
                'sender_data' => new SenderResource(auth('api')->user())
            ];

            $user->notify(new CancelOrderNotification($main_order, ['database']));

            $admins = User::whereIn('user_type', ['superadmin', 'admin'])->get();
            Notification::send($admins, new CancelOrderNotification($main_order, ['database', 'broadcast']));
            pushFcmNotes($fcm_notes, $user->devices);
            return (new SingleOrderResource($main_order))->additional(['status' => 'success', 'message' => '']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['data' => null, 'status' => 'fail', 'message' => trans('api.messages.there_is_an_error_try_again')], 400);
        }
    }

    public function withTheChildOrder($order_id)
    {

            $order = MainOrder::where('client_id', auth('api')->id())->findOrFail($order_id);
            $service_id = $order->to == 'sitter' ? optional($order->sitter_order)->service_id : optional($order->center_order)->service_id;
            if ($service_id == Statuses::HOUR_SERVICE) {
                $sitter_order = SitterOrder::where(['status' => Statuses::WAITING,  'main_order_id' => $order->id])->firstOrFail();
            } else {

                $order_for_sitter = SitterOrder::where(['status' => Statuses::PROCESS, 'main_order_id' => $order->id])->firstOrFail();

                $sitter_order = $order_for_sitter->months->month_dates()->where('order_month_dates.status', Statuses::WAITING)->orderBy('order_month_dates.date', 'ASC')->firstOrFail();
                // dd('ss');
                // dd($sitter_order);
            }


            if (isset($sitter_order) && $sitter_order) {

                $sitter_order->update(['status' => 'with_the_child']);

                $order->refresh();
                $fcm_notes = [
                    'title' => trans('dashboard.notification.sitter_has_been_recieved_childern_title',[],$order->sitter->current_lang),
                    'body' => trans('dashboard.notification.sitter_has_been_recieved_childern_body',[],$order->sitter->current_lang).auth('api')->user()->name,
                    'sender_data' => new SenderResource(auth('api')->user())
                ];
                $order->client->notify(new RecieveChildernNotification($order, ['database']));

                $admins = User::whereIn('user_type', ['superadmin', 'admin'])->get();
                Notification::send($admins, new RecieveChildernNotification($order, ['database', 'broadcast']));
                pushFcmNotes($fcm_notes, optional($order->client)->devices);
            }
            return (new SingleOrderResource($order))->additional(['status' => 'success', 'message' => trans('api.messages.order_status_has_been_changed_to_with_the_child')]);
            // return response()->json(['data' => null, 'status' => 'success', 'message' => trans('api.messages.order_status_has_been_changed_to_with_the_child')]);


    }
    public function completeOrder($order_id)
    {
        $order = MainOrder::where('client_id', auth('api')->id())->findOrFail($order_id);
        DB::beginTransaction();

        try {
            $service_id = $order->to == 'sitter' ? optional($order->sitter_order)->service_id : optional($order->center_order)->service_id;

            if ($service_id == Statuses::HOUR_SERVICE) {
                $this->completeOrderForHourUsingScanQrCode($order);
            } else {

                $order_for_sitter = SitterOrder::where(['status' => 'process', 'main_order_id' => $order->id])->firstOrFail();

                $sitter_order = $order_for_sitter->months->month_dates()->whereIn('order_month_dates.status', ['waiting', 'with_the_child'])->orderBy('order_month_dates.date', 'ASC')->firstOrFail();
                $sitter_order->update(['order_month_dates.status' => 'completed']);
                $last_day = OrderMonthDate::where('order_month_id', $order->sitter_order->months->id)->orderBy('date', 'DESC')->firstOrFail();
                // dd($last_day == $sitter_order);
                if ($last_day->id == $sitter_order->id) {

                    // $sitter_order->update(['status' => 'with_the_child']);
                    $order->sitter_order()->update(['status' => Statuses::COMPLETED]);
                    $this->chargeWalletForProvider($order, $order->client, Statuses::CANCELED,$order->sitter_order);
                    $this->chargeWalletForProvider($order, $order->sitter, Statuses::COMPLETED,$order->sitter_order);

                }
            }
            DB::commit();
            $order->refresh();
            $fcm_notes = [
                'title' => trans('dashboard.notification.sitter_has_been_deliver_childern_title',[],$order->sitter->current_lang),
                'body' => trans('dashboard.notification.sitter_has_been_deliver_childern_body',[],$order->sitter->current_lang).auth('api')->user()->name,
                'sender_data' => new SenderResource(auth('api')->user())
            ];
            $order->client->notify(new DeliverChildernNotification($order, ['database']));

            $admins = User::whereIn('user_type', ['superadmin', 'admin'])->get();
            pushFcmNotes($fcm_notes, optional($order->client)->devices);
            Notification::send($admins, new DeliverChildernNotification($order, ['database', 'broadcast']));
            return (new SingleOrderResource($order))->additional(['status' => 'success', 'message' => trans('api.messages.completation_has_been_done')]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['data' => null, 'status' => 'fail', 'message' => trans('api.messages.there_is_an_error_try_again')], 400);
        }
    }
}

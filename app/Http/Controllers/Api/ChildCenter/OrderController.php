<?php

namespace App\Http\Controllers\Api\ChildCenter;

use App\Classes\OrderStatuses;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Client\Order\NewOrderResource;
use App\Http\Resources\Api\Client\Order\OrderResource;
use App\Http\Resources\Api\Client\Order\SingleCenterResource;
use App\Http\Resources\Api\Client\Order\SingleOrderResource;
use App\Http\Resources\Api\Notification\SenderResource;
use App\Models\CenterOrder;
use App\Models\MainOrder;
use App\Models\OrderMonthDate;
use App\Models\User;
use App\Models\Wallet;
use App\Notifications\Orders\AcceptOrderNotification;
use App\Notifications\Orders\ActiveOrderNotification;
use App\Notifications\Orders\CancelOrderNotification;
use App\Notifications\Orders\CompleteOrderNotification;
use App\Notifications\Orders\RejectOrderNotification;
use App\Traits\AppProfit;
use App\Traits\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    use Order;
    public $order;
    public function __construct(OrderStatuses $order)
    {
        $this->order = $order;
    }
    public function getNewAndActiveOrders(Request $request)
    {


        $orders = MainOrder::where(['to' => 'center', 'center_id' => auth('api')->id()])->when(isset($request->order_type), function ($q) use ($request) {
            $q->whereHas('center_order', function ($q) use ($request) {
                if ($request->order_type == 'new_orders') {
                    $q->where('status', 'pending');
                } elseif ($request->order_type == 'active_orders') {
                    $q->whereIn('status', ['active','process']);
                }
            });
        })->get();

        return NewOrderResource::collection($orders)->additional(['status' => 'success', 'message' => '']);
        // return response()->json(['data'=>$data,'status'=>'success','message'=>'']);
    }

    public function getWaitingAndExpiredOrders()
    {
        $data = [];
        $waiting_orders = MainOrder::where(['to' => 'center', 'center_id' => auth('api')->id()])->whereHas('center_order', function ($q) {
            $q->where('status', 'waiting');
        })->get();
        $data['waiting_orders'] = NewOrderResource::collection($waiting_orders);
        $expired_orders = MainOrder::where(['to' => 'center', 'center_id' => auth('api')->id()])->whereHas('center_order', function ($q) {
            $q->whereIn('status', ['rejected', 'completed', 'canceled']);
        })->get();
        $data['expired_orders'] = NewOrderResource::collection($expired_orders);
        return response()->json(['data' => $data, 'status' => 'success', 'message' => '']);
    }

    public function getOrderDetails($order_id)
    {
        return $this->order->getDetailsForOrder($order_id, 'center_id');
    }

    public function acceptOrder($order_id)
    {
        $order = MainOrder::where('center_id', auth('api')->id())->findOrFail($order_id);

        $center_order = CenterOrder::where('status', 'pending')->findOrFail(optional($order->center_order)->id);
        if (optional($center_order->service)->service_type == 'hour') {
            $center_order->update(['status' => 'waiting']);
        } else {
            $center_order->update(['status' => 'process']);
        }
        $this->chargeWallet($center_order->price, $center_order->center_id);
        $order->refresh();
        //   dd($center_order);
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

    public function rejectOrder($order_id)
    {
        DB::beginTransaction();

        try {

            $order = MainOrder::where('center_id', auth('api')->id())->findOrFail($order_id);

            $center_order = CenterOrder::where('status', 'pending')->findOrFail(optional($order->center_order)->id);
            $center_order->update(['status' => 'rejected']);
            if ($center_order->pay_type == 'wallet') {
                $this->chargeWallet($center_order->price, $center_order->client_id);
            }
            $order->client->notify(new RejectOrderNotification($order, ['database']));

            $admins = User::whereIn('user_type', ['superadmin', 'admin'])->get();
            Notification::send($admins, new RejectOrderNotification($order, ['database', 'broadcast']));
            DB::commit();
            return (new SingleOrderResource($order))->additional(['status' => 'success', 'message' => '']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['data' => null, 'status' => 'fail', 'message' => trans('api.messages.there_is_an_error_try_again')], 400);
        }
    }

    public function cancelOrder($order_id)
    {
        DB::beginTransaction();

        try {
            $main_order = MainOrder::where('center_id', auth('api')->id())->findOrFail($order_id);

            $fcm_notes = [
                'title' => ['dashboard.notification.order_has_been_rejected_title'],
                'body' => ['dashboard.notification.order_has_been_rejected_body', ['body' => auth('api')->user()->name ?? auth('api')->user()->phone]],
                'sender_data' => new SenderResource(auth('api')->user())
            ];
            // $main_order->sitter_order()->whereIn('status',['pending','waiting'])->update(['status'=>'canceled']);
            $center_order = CenterOrder::findOrFail($main_order->center_order->id);
            if ($center_order->status == 'waiting' || $center_order->status == 'process') {
                $center_order_period = optional($center_order->service)->service_type == 'hour'
                    ? $center_order->hours()->whereBetween('date', [Carbon::now(), Carbon::now()->addDay()])->first()
                    : $center_order->months()->whereBetween('start_date', [Carbon::now(), Carbon::now()->addDay()])->first();
            }

            if (isset($center_order_period) && $center_order_period) {
                return response()->json(['data' => null, 'status' => 'fail', 'message' => __('api.messages.cannot_cancel_order_before_start_by_24_hour')], 400);
            }
            $center_order->update(['status' => 'canceled']);
            if ($center_order->pay_type == 'wallet') {

                $this->chargeWallet($main_order->price_after_offer, $center_order->client_id);
            }
            $main_order->client->notify(new CancelOrderNotification($main_order, ['database']));

            $admins = User::whereIn('user_type', ['superadmin', 'admin'])->get();
            Notification::send($admins, new CancelOrderNotification($main_order, ['database', 'broadcast']));
            pushFcmNotes($fcm_notes, optional($main_order->client)->devices);
            DB::commit();
            return (new SingleOrderResource($main_order))->additional(['status' => 'success', 'message' => '']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['data' => null, 'status' => 'fail', 'message' => trans('api.messages.there_is_an_error_try_again')], 400);
        }
    }

    public function activeOrder($order_id)
    {
        $main_order = MainOrder::where('center_id', auth('api')->id())->findOrFail($order_id);
        $fcm_notes = [
            'title' => ['dashboard.notification.order_has_been_active_title'],
            'body' => ['dashboard.notification.order_has_been_active_body', ['body' => auth('api')->user()->name ?? auth('api')->user()->phone]],
            'sender_data' => new SenderResource(auth('api')->user())
        ];
        // $main_order->sitter_order()->whereIn('status',['pending','waiting'])->update(['status'=>'canceled']);
        $center_order = CenterOrder::findOrFail($main_order->center_order->id);
        if (optional($center_order->service)->service_type == 'hour') {
            $center_order->update(['status' => 'active']);
        } else {
            $center_order_month = $center_order->months->month_dates()->where('status', 'waiting')->orderBy('date', 'ASC')->first();
            if (isset($center_order_month) && $center_order_month) {

                $center_order_month->update(['status' => 'active']);
            }
        }
        $main_order->refresh();
        $main_order->client->notify(new ActiveOrderNotification($main_order, ['database']));

        $admins = User::whereIn('user_type', ['superadmin', 'admin'])->get();
        Notification::send($admins, new ActiveOrderNotification($main_order, ['database', 'broadcast']));
        pushFcmNotes($fcm_notes, optional($main_order->client)->devices);
        return (new SingleOrderResource($main_order))->additional(['status' => 'success', 'message' => '']);
    }

    public function completeOrder($order_id)
    {
        DB::beginTransaction();

        try {
            $main_order = MainOrder::where('center_id', auth('api')->id())->findOrFail($order_id);
            $fcm_notes = [
                'title' => ['dashboard.notification.order_has_been_completed_title'],
                'body' => ['dashboard.notification.order_has_been_completed_body', ['body' => auth('api')->user()->name ?? auth('api')->user()->phone]],
                'sender_data' => new SenderResource(auth('api')->user())
            ];
            // $main_order->sitter_order()->whereIn('status',['pending','waiting'])->update(['status'=>'canceled']);
            $center_order = CenterOrder::findOrFail($main_order->center_order->id);
            if (optional($center_order->service)->service_type == 'hour') {
                $center_order->update(['status' => 'completed']);
                $main_order->update(['finished_at' => now()]);
                if ($center_order->pay_type == 'wallet') {
                    $center = User::findOrFail($main_order->center_id);
                    $wallet_before = $center->wallet;
                    $this->chargeWallet($main_order->final_price, $main_order->center_id);
                    Wallet::create(['amount' => $main_order->final_price, 'wallet_before' => $wallet_before, 'wallet_after' => $center->wallet, 'user_id' => $main_order->center_id, 'transferd_by' => $main_order->client_id, 'order_id' => $main_order->id]);
                }
                $main_order->client->notify(new CompleteOrderNotification($main_order, ['database']));

                $admins = User::whereIn('user_type', ['superadmin', 'admin'])->get();
                Notification::send($admins, new CompleteOrderNotification($main_order, ['database', 'broadcast']));
                pushFcmNotes($fcm_notes, optional($main_order->client)->devices);
            } else {
                $center_order_month = $center_order->months->month_dates()->where('status', 'active')->first();
                if (isset($center_order_month) && $center_order_month) {
                    $center_order_month->update(['status' => 'completed']);
                }
                if (isset($center_order_month) && $center_order_month) {
                    $last_day = OrderMonthDate::where('order_month_id', $main_order->center_order->months->id)->orderBy('date', 'DESC')->firstOrFail();
                }
                // dd($last_day->id == $center_order_month->id);
                if (isset($last_day) && $last_day->id == $center_order_month->id) {
                    $center_order->update(['status' => 'completed']);
                    if ($center_order->pay_type == 'wallet') {
                        $total_canceled_price = 0;
                        $total_completed_price = 0;
                        $canceled_dates = OrderMonthDate::where(['order_month_id' => optional($center_order->months)->id, 'status' => 'canceled'])->get();

                        $completed_dates = OrderMonthDate::where(['order_month_id' => optional($center_order->months)->id, 'status' => 'completed'])->get();
                        foreach ($canceled_dates as $cancel_date) {
                            $start_time = optional($cancel_date->order_day)->start_time;
                            $end_time = optional($cancel_date->order_day)->end_time;
                            $hours = $end_time->diffInHours($start_time);
                            $total_canceled_price += ($hours * optional($cancel_date->month)->price_per_hour_for_month);
                        }
                        foreach ($completed_dates as $completed_date) {
                            $start_time = optional($completed_date->order_day)->start_time;
                            $end_time = optional($completed_date->order_day)->end_time;
                            $hours = $end_time->diffInHours($start_time);
                            $total_completed_price += ($hours * optional($completed_date->month)->price_per_hour_for_month);
                        }
                        // dd($total_completed_price);

                        if ($total_canceled_price > 0) {
                            $client = $center_order->client;
                            $client_wallet_before = $client->wallet;
                            $client_wallet_after = $client->wallet + $total_canceled_price;
                            Wallet::create(['amount' => $total_canceled_price, 'wallet_before' => $client_wallet_before, 'wallet_after' => $client_wallet_after, 'user_id' => $main_order->client_id, 'transferd_by' => $order->center_id, 'order_id' => $main_order->id]);
                            $this->chargeWallet($total_canceled_price, $center_order->client_id);
                        }
                        if ($total_completed_price > 0) {
                            $center = $center_order->center;
                            $center_wallet_before = $center->wallet;
                            $center_wallet_after = $center->wallet + $total_completed_price;

                            Wallet::create(['amount' => $total_completed_price, 'wallet_before' => $center_wallet_before, 'wallet_after' => $center_wallet_after, 'user_id' => $main_order->center_id, 'transferd_by' => $main_order->client_id, 'order_id' => $main_order->id]);
                            $this->chargeWallet($total_completed_price, $center_order->center_id);
                        }
                    }
                    $main_order->client->notify(new CompleteOrderNotification($main_order, ['database']));

                    $admins = User::whereIn('user_type', ['superadmin', 'admin'])->get();
                    Notification::send($admins, new CompleteOrderNotification($main_order, ['database', 'broadcast']));
                    pushFcmNotes($fcm_notes, optional($main_order->client)->devices);
                }
            }
            $main_order->refresh();
            DB::commit();
            return (new SingleOrderResource($main_order))->additional(['status' => 'success', 'message' => '']);

            // all good
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['data' => null, 'status' => 'fail', 'message' => trans('api.messages.there_is_an_error_try_again')], 400);
        }
    }
}

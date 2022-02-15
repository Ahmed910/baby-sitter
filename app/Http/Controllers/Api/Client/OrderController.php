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
use App\Models\OrderMonthDate;
use App\Models\SitterOrder;
use App\Models\User;
use App\Models\Wallet;
use App\Notifications\Orders\CancelOrderNotification;
use App\Notifications\Orders\DeliverChildernNotification;
use App\Notifications\Orders\RecieveChildernNotification;
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
                'body' => ['dashboard.notification.client_cancel_order_body'],
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
        DB::beginTransaction();

        try {
            $order = MainOrder::where('client_id', auth('api')->id())->findOrFail($order_id);
            $service_id = $order->to == 'sitter' ? optional($order->sitter_order)->service_id : optional($order->center_order)->service_id;
            if ($service_id == HOUR_SERVICE) {
                $sitter_order = SitterOrder::where(['status' => 'waiting',  'main_order_id' => $order->id])->firstOrFail();
            } else {

                $order_for_sitter = SitterOrder::where(['status' => 'process', 'main_order_id' => $order->id])->firstOrFail();

                $sitter_order = $order_for_sitter->months->month_dates()->where('status', 'waiting')->orderBy('date', 'ASC')->firstOrFail();
                // dd('ss');
                // dd($sitter_order);
            }


            if (isset($sitter_order) && $sitter_order) {

                $sitter_order->update(['status' => 'with_the_child']);
                DB::commit();
                $order->refresh();
                $fcm_notes = [
                    'title' => ['dashboard.notification.sitter_has_been_recieved_childern_title'],
                    'body' => ['dashboard.notification.sitter_has_been_recieved_childern_body'],
                    'sender_data' => new SenderResource(auth('api')->user())
                ];
                $order->client->notify(new RecieveChildernNotification($order, ['database']));

                $admins = User::whereIn('user_type', ['superadmin', 'admin'])->get();
                Notification::send($admins, new RecieveChildernNotification($order, ['database', 'broadcast']));
                pushFcmNotes($fcm_notes, optional($order->client)->devices);
            }
            return (new SingleOrderResource($order))->additional(['status' => 'success', 'message' => trans('api.messages.order_status_has_been_changed_to_with_the_child')]);
            // return response()->json(['data' => null, 'status' => 'success', 'message' => trans('api.messages.order_status_has_been_changed_to_with_the_child')]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['data' => null, 'status' => 'fail', 'message' => trans('api.messages.there_is_an_error_try_again')], 400);
        }
    }
    public function completeOrder($order_id)
    {
        DB::beginTransaction();

        try {
        $order = MainOrder::where('client_id', auth('api')->id())->findOrFail($order_id);
        $service_id = $order->to == 'sitter' ? optional($order->sitter_order)->service_id : optional($order->center_order)->service_id;

        if ($service_id == HOUR_SERVICE) {
            $sitter_order = SitterOrder::where(['status' => 'with_the_child',  'main_order_id' => $order->id])->firstOrFail();
            $order->update(['finished_at' => now()]);
            $sitter_order->update(['status'=>'completed']);
            if ($sitter_order->pay_type == 'wallet') {

                $sitter = User::findOrFail($sitter_order->sitter_id);
                $wallet_before = $sitter->wallet;
                $this->chargeWallet($order->final_price, $sitter_order->sitter_id);
                Wallet::create(['amount' => $order->final_price, 'wallet_before' => $wallet_before, 'wallet_after' => $sitter->wallet, 'user_id' => $order->sitter_id, 'transferd_by' => $order->client_id, 'order_id' => $order->id]);
            }

        } else {
            //    dd($sitter_order);
            //     $sitter_order_month = $sitter_order->months->month_dates()->where('status','with_the_child')->first();
            //     dd($sitter_order_month);
            // $start_time = optional($center_order_month->order_day)->start_time;
            // $end_time = optional($center_order_month->order_day)->end_time;
            // $hours = $end_time->diffInHours($start_time);
            // $price = $hours*optional($center_order->months)->price_per_hour_for_month;
            // $this->chargeWallet($price,$center_order->client_id);
            // dd($sitter_order);
            $order_for_sitter = SitterOrder::where(['status' => 'process', 'main_order_id' => $order->id])->firstOrFail();

            $sitter_order = $order_for_sitter->months->month_dates()->where('status', 'waiting')->orderBy('date', 'ASC')->firstOrFail();
            $sitter_order->update(['status'=>'with_the_child']);
            if (isset($sitter_order) && $sitter_order) {
                $last_day = OrderMonthDate::where('order_month_id',$order->sitter_order->months->id)->orderBy('date', 'DESC')->firstOrFail();
                // dd($last_day == $sitter_order);
            }

            if ($last_day->id == $sitter_order->id) {

                $order->sitter_order()->update(['status'=>'completed']);
                if (optional($order->sitter_order)->pay_type == 'wallet') {
                    $total_canceled_price = 0;
                    $total_completed_price = 0;
                    $canceled_dates = OrderMonthDate::where(['order_month_id' => optional($sitter_order->month)->id, 'status' => 'canceled'])->get();

                    $completed_dates = OrderMonthDate::where(['order_month_id' => optional($sitter_order->month)->id, 'status' => 'completed'])->get();

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



                    if ($total_canceled_price > 0) {

                        $client = $order->client;
                        $client_wallet_before = $client->wallet;
                        $client_wallet_after = $client->wallet + $total_canceled_price;
                        Wallet::create(['amount' => $total_canceled_price, 'wallet_before' => $client_wallet_before, 'wallet_after' => $client_wallet_after, 'user_id' => $order->client_id, 'transferd_by' => $order->sitter_id, 'order_id' => $order->id]);
                        $this->chargeWallet($total_canceled_price, optional($order->sitter_order)->client_id);
                    }
                    if ($total_completed_price > 0) {

                        $sitter = $order->sitter;
                        $sitter_wallet_before = $sitter->wallet;
                        $sitter_wallet_after = $sitter->wallet + $total_completed_price;
                        Wallet::create(['amount' => $total_completed_price, 'wallet_before' => $sitter_wallet_before, 'wallet_after' => $sitter_wallet_after, 'user_id' => $order->sitter_id, 'transferd_by' => $order->client_id, 'order_id' => $order->id]);
                        $this->chargeWallet($total_completed_price, optional($order->sitter_order)->sitter_id);
                    }

                    //  Wallet::create(['amount'=>$order->final_price,'wallet_before'=>$wallet_before,'wallet_after'=>$sitter->wallet,'user_id'=>$order->sitter_id,'transferd_by'=>$order->client_id,'order_id'=>$order->id]);

                }
            }
        }


        DB::commit();
        $order->refresh();
        $fcm_notes = [
            'title' => ['dashboard.notification.sitter_has_been_deliver_childern_title'],
            'body' => ['dashboard.notification.sitter_has_been_deliver_childern_body'],
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

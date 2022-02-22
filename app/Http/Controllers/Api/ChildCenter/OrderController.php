<?php

namespace App\Http\Controllers\Api\ChildCenter;

use App\Classes\{OrderStatuses,Statuses};
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Client\Order\{NewOrderResource, SingleOrderResource};
use App\Http\Resources\Api\Notification\SenderResource;
use App\Models\{CenterOrder, MainOrder, OrderMonthDate, User, Wallet};
use App\Notifications\Orders\{AcceptOrderNotification, ActiveOrderNotification, CancelOrderNotification, CompleteOrderNotification, RejectOrderNotification};
use App\Traits\{CompleteOrderHourService, CompleteOrderMonthService, Order};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Notification};

class OrderController extends Controller
{
    use Order, CompleteOrderHourService,CompleteOrderMonthService;
    public $order;
    public function __construct(OrderStatuses $order)
    {
        $this->order = $order;
    }
    public function getNewAndActiveOrders(Request $request)
    {

        // dd(auth('api')->user());
        $orders = MainOrder::where(['to' => 'center', 'center_id' => auth('api')->id()])->when(isset($request->order_type), function ($q) use ($request) {
            $q->whereHas('center_order', function ($q) use ($request) {
                if ($request->order_type == 'new_orders') {
                    $q->where('status', 'pending');
                } elseif ($request->order_type == 'active_orders') {
                    $q->whereIn('status', ['active', 'process']);
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
            'title' => trans('dashboard.notification.order_has_been_accepted_title', [], $order->client->current_lang),
            'body' => trans('dashboard.notification.order_has_been_accepted_body', [], $order->client->current_lang) . auth('api')->user()->name,
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

            $this->chargeWallet($center_order->price, $center_order->client_id);

            DB::commit();
            $fcm_notes = [
                'title' => trans('dashboard.notification.order_has_been_rejected_title', [], $order->client->current_lang),
                'body' => trans('dashboard.notification.order_has_been_rejected_body', [], $order->client->current_lang) . auth('api')->user()->name,
                'sender_data' => new SenderResource(auth('api')->user())
            ];
            $order->refresh();
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

    public function cancelOrder($order_id)
    {
        DB::beginTransaction();

        try {
            $main_order = MainOrder::where('center_id', auth('api')->id())->findOrFail($order_id);

            $fcm_notes = [
                'title' => trans('dashboard.notification.order_has_been_rejected_title', [], $main_order->client->current_lang),
                'body' => trans('dashboard.notification.order_has_been_rejected_body', [], $main_order->client->current_lang) . auth('api')->user()->name,
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


            $this->chargeWallet($main_order->price_after_offer, $center_order->client_id);
            DB::commit();
            $main_order->refresh();
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

    public function activeOrder($order_id)
    {
        $main_order = MainOrder::where('center_id', auth('api')->id())->findOrFail($order_id);
        $fcm_notes = [
            'title' => trans('dashboard.notification.order_has_been_active_title', [], $main_order->client->current_lang),
            'body' => trans('dashboard.notification.order_has_been_active_body', [], $main_order->client->current_lang) . auth('api')->user()->name,
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
        $main_order = MainOrder::where('center_id', auth('api')->id())->findOrFail($order_id);

        // $main_order->sitter_order()->whereIn('status',['pending','waiting'])->update(['status'=>'canceled']);
        $center_order = CenterOrder::findOrFail($main_order->center_order->id);
        if (optional($center_order->service)->service_type == 'hour') {
            return $this->completeOrderForHourServiceForCenter($main_order);
        } else {
            // $center_order = CenterOrder::findOrFail($main_order->center_order->id);
            $center_order = CenterOrder::where(['status' => Statuses::PROCESS, 'main_order_id' => $main_order->id])->firstOrFail();
            // $center_order_month = $center_order->months->month_dates()->where('status', 'active')->first();
            $center_order_month = $center_order->months->month_dates()->where('order_month_dates.status', Statuses::ACTIVE)->orderBy('order_month_dates.date', 'ASC')->firstOrFail();
            $last_day = OrderMonthDate::where('order_month_id', $main_order->center_order->months->id)->orderBy('date', 'DESC')->first();
            DB::beginTransaction();
            try {
                $center_order_month->update(['order_month_dates.status' => Statuses::COMPLETED]);
                // dd($last_day->id == $center_order_month->id);
                if (isset($last_day) && $last_day->id == $center_order_month->id) {
                    $center_order->update(['status' => Statuses::COMPLETED]);
                    $this->chargeWalletForProvider($main_order, $main_order->client, Statuses::CANCELED, $main_order->center_order);
                    $this->chargeWalletForProvider($main_order, $main_order->center, Statuses::COMPLETED, $main_order->center_order);
                }
                $main_order->refresh();
                DB::commit();
                $fcm_notes = [
                    'title' => trans('dashboard.notification.order_has_been_completed_title', [], $main_order->client->current_lang),
                    'body' => trans('dashboard.notification.order_has_been_completed_body', [], $main_order->client->current_lang) . auth('api')->user()->name,
                    'sender_data' => new SenderResource(auth('api')->user())
                ];
                $main_order->client->notify(new CompleteOrderNotification($main_order, ['database']));

                $admins = User::whereIn('user_type', ['superadmin', 'admin'])->get();
                Notification::send($admins, new CompleteOrderNotification($main_order, ['database', 'broadcast']));
                pushFcmNotes($fcm_notes, optional($main_order->client)->devices);
                return (new SingleOrderResource($main_order))->additional(['status' => 'success', 'message' => trans('api.messages.order_has_been_completed')]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['data' => null, 'status' => 'fail', 'message' => trans('api.messages.there_is_an_error_try_again')], 400);
            }
        }
    }
}

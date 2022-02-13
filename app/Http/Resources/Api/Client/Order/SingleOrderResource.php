<?php

namespace App\Http\Resources\Api\Client\Order;

use App\Http\Resources\Api\Help\ServiceResource;
use App\Http\Resources\Api\User\RateForBabySitterResource;
use App\Http\Resources\Api\User\RateForSpecificOrderResource;
use App\Models\Rate;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $order = $this->to == 'sitter' ? $this->sitter_order : $this->center_order;

        // $order_period_before_day_from_starting = optional($order->service)->service_type == 'hour'
        //            $order->hours()->where('date','>',Carbon::now())->whereNotBetween('date', [Carbon::now(), Carbon::now()->addDay()])->first()
        //             : $order->months()->where('start_date','>',Carbon::now())->whereNotBetween('start_date', [Carbon::now(), Carbon::now()->addDay()])->first();
        // $passed_time = optional($order->service)->service_type == 'hour' ? $order->hours()->where('date','<',now())->first()

        // $passed_time =$order->hours()->where('date','<',now())->first();

        //   dd(optional($order->service)->service_type);


        return [
            'id' => $this->id,
            'type' => $this->to,
            'total_price' => (float)$this->price_after_offer,

            'chat_id' => $this->when(isset($this->sitter_order), optional($this->chat)->id),
            'sitter_rate' => $this->when(isset($this->sitter_order) && optional($this->sitter_order)->status == 'completed', new RateForSpecificOrderResource(Rate::where(['from' => auth('api')->id(), 'order_id' => $this->id])->where('to', '<>', null)->first())),
            'center_rate' => $this->when(isset($this->center_order) && optional($this->center_order)->status == 'completed', new RateForSpecificOrderResource(Rate::where(['from' => auth('api')->id(), 'order_id' => $this->id])->where('to_center', '<>', null)->first())),
            'baby_sitter_rate' => $this->when(isset($this->center_order) && optional($this->center_order)->status == 'completed', new RateForBabySitterResource(Rate::where(['from' => auth('api')->id(), 'order_id' => $this->id])->where('to_baby_sitter', '<>', null)->first())),
            'client_rate' => $this->when(((isset($this->center_order) && optional($this->center_order)->status == 'completed') || (isset($this->sitter_order) && optional($this->sitter_order)->status == 'completed')) && (auth('api')->user()->user_type != 'client'), new RateForSpecificOrderResource(Rate::where(['from' => auth('api')->id(), 'order_id' => $this->id])->where('to_client', '<>', null)->first())),
            'sitter_worker_id' => $this->when(isset($this->center_order),$order->baby_sitter_id),
            //  'order_data'=> new OrderDetailsResource($this->to == 'sitter' ? $this->sitter_order:$this->center_order)
            'status' => $order->status,
            'cancel_status' => $this->cancelStatus($order),
            'check_waiting' => $this->waitingCheck($order),
            // 'provider_name'=>$this->when(auth('api')->user()->user_type == 'client',$this->to == 'sitter' ? optional($this->sitter)->name : optional($this->center)->name),
            'provider' => $this->when(auth('api')->user()->user_type == 'client', new UserDataResource($this->to == 'sitter' ? $this->sitter : $this->center)),
            'client' => new UserDataResource($this->client),
            'sitter_worker_name_in_center' => $this->when($this->to == 'center', optional($order->baby_sitter)->name),
            'customer_data' => $this->when(auth('api')->user()->user_type == 'babysitter', [
                'customer_id' => optional($this->client)->id,
                'customer_name' => optional($this->client)->name,
                'api_info' => [
                    'type' => 'GET',
                    'params' => 'user_id',
                    'headers' => ['token' => 'Bearer token', 'accept' => 'application/json'],
                    'url' => route('order.customer_profile', ['customer_id' => optional($this->client)->id])
                ]
            ]),
            'qr_code' => $this->when(auth('api')->user()->user_type == 'babysitter' && $this->to == 'sitter', $order->qrCode),
            'gender' => $this->when(auth('api')->user()->user_type == 'babysitter' && $this->to == 'sitter', optional($order->client)->gender),
            'child_center_location' => $this->when(auth('api')->user()->user_type == 'client' && $this->to == 'center', optional(optional($this->center)->profile)->location),
            'lat' => $this->when(auth('api')->user()->user_type == 'client' && $this->to == 'center', optional(optional($this->center)->profile)->lat),
            'lng' => $this->when(auth('api')->user()->user_type == 'client' && $this->to == 'center', optional(optional($this->center)->profile)->lng),

            'customer_location' => $this->when(auth('api')->user()->user_type != 'childcenter' && $this->to == 'sitter', $order->location),
            'customer_lat' => $this->when(auth('api')->user()->user_type != 'childcenter' && $this->to == 'sitter', $order->lat),
            'customer_lng' => $this->when(auth('api')->user()->user_type != 'childcenter' && $this->to == 'sitter', $order->lng),
            'service_type' => optional($order->service)->service_type,
            // 'service'=> new ServiceResource($order->service),
            'service_details' => optional($order->service)->service_type == 'hour' ? new HourOrderResource($order->hours) : new MonthOrderResource($order->months),
            'next_day'=> optional($order->service)->service_type == 'month' ? new MonthDaysInOrderResource($order->months->month_dates()->where('order_month_dates.status','waiting')->orderBy('order_month_dates.date','ASC')->first()):null,
            // 'days_in_month' => $this->when(optional($order->service)->service_type == 'month', isset($order->months) ? OrderDaysInMonthResource::collection($order->months->month_days) : null),
            'kids' => OrderKidsResource::collection($order->kids),
            'comment' => $order->comment,

            // 'rate'=>$order->when($order->status == 'completed',new RateForSpecificOrderResource($rate)),
            'created_at' => $order->created_at->toFormattedDateString()

            // 'sitter_order'=> $this->when(isset($this->sitter_order),new SingleSitterOrderResource(optional($this->sitter_order))) ,
            // 'center_order' => $this->when(isset($this->center_order),new SingleCenterResource(optional($this->center_order))) ,
        ];
    }

    private function waitingCheck($order)
    {
        if (optional($order->service)->service_type == 'hour') {
            $passed_time = $order->hours()->where('date', '=', now()->format('Y-m-d'))->first();
            if (isset($passed_time) && $order->status == 'waiting') {
                $waiting_check = true;
            } else {
                $waiting_check = false;
            }
        } else {
            $passed_time = $order->months->month_dates()->where('date', '=', now()->format('Y-m-d'))->first();
            if (isset($passed_time) && $order->status == 'process') {
                $waiting_check = true;
            } else {
                $waiting_check = false;
            }
            $waiting_check = false;
        }

        return $waiting_check;
    }

    private function cancelStatus($order)
    {

        $order_period_before_day_from_starting = optional($order->service)->service_type == 'hour'
            ? $order->hours()->where('date', '>', Carbon::now())->whereNotBetween('date', [Carbon::now(), Carbon::now()->addDay()])->first()
            : $order->months()->where('start_date', '>', Carbon::now())->whereNotBetween('start_date', [Carbon::now(), Carbon::now()->addDay()])->first();
        if (in_array($order->status, ['waiting', 'pending']) && isset($order_period_before_day_from_starting) &&  $order_period_before_day_from_starting) {
            $cancel_status = true;
        } else {
            $cancel_status = false;
        }
        return $cancel_status;
    }
}

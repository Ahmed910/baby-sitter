<?php

namespace App\Http\Resources\Api\Client\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $status_trans = trans('dashboard.order.client.'.$this->order_type.".".$this->order_status);

        return [
          'id' => $this->id,
          'created_at' => $this->created_at->format('Y-m-d'),

          'total_price' => (string)$this->total_price,
          'order_status' => $this->order_status,
          'order_status_trans' => $status_trans,

          'order_type' => $this->order_type,
          'order_types_trans' => trans('dashboard.order.order_types.'.$this->order_type),
          'order_details' => (string) $this->order_details,

          'start_location' => $this->start_location_data,
          'end_location' => $this->end_location_data,

          'badget' => (string)$this->budget,
          'pay_type' => (string)$this->pay_type,
          'share_link_uuid' => (string)$this->share_link_uuid,
          'share_link' => "",
          'is_client_recieved_order' => (boolean)$this->client_recieved_order,
          // 'has_offers' => $this->offers()->exists(),
          // 'is_client_rate' => $this->rates()->where('rates.client_id',auth('api')->id())->exists(),
          // 'offers_count' => $this->offers->count(),
          // 'offers' => OfferResource::collection($this->offers->take(5)),
          // 'accepted_offer' => new OfferResource($this->offers()->firstWhere('price_offer_status','accepted')),
          'driver' => $this->driver_id ? [
              'driver_id' => $this->driver_id,
              'city_id' => optional(optional($this->driver)->profile)->city_id,
              'city_name' => optional($this->driver)->city_name,
              'phone' => $this->driver->phone,
              'fullname' => $this->driver->fullname,
              'image' => $this->driver->avatar,
              'rate' => (float)$this->driver->rate_avg,
           ]: null,
        ];
    }
}

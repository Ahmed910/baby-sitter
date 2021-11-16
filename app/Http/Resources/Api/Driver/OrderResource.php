<?php

namespace App\Http\Resources\Api\Driver;

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
        $status_trans = trans('dashboard.order.driver.'.$this->order_type.".".$this->order_status);



        return [
          'id' => $this->id,
          'created_at' => $this->created_at->format('Y-m-d'),

          'total_price' => (string)$this->total_price,
          'order_status' => $this->order_status,
          'order_status_trans' => $status_trans,

          'order_type' => $this->order_type,
          'order_types_trans' => trans('dashboard.order.order_types.'.$this->order_type),
          'order_details' => (string)$this->order_details,

          'start_location' => $this->start_location_data,
          'end_location' => $this->end_location_data,

          'badget' => (string)$this->budget,

          'share_link_uuid' => (string)$this->share_link_uuid,
          'share_link' => "",
          'is_client_recieved_order' => (boolean)$this->client_recieved_order,

          'pay_type' => (string)$this->pay_type,
          'offer' => new OfferResource($this->offers()->firstWhere('driver_id',auth('api')->id())),
          'client' =>  [
              'client_id' => $this->client_id,
              'phone' => $this->client->phone,
              'fullname' => $this->client->fullname,
              'image' => $this->client->avatar,
          ],

        ];
    }
}

<?php

namespace App\Http\Resources\Dashboard\Client;

use Illuminate\Http\Resources\Json\JsonResource;

class SingleClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'avatar' => $this->avatar,
            'fullname' => $this->fullname,
            'email' => $this->email,
            'phone' => $this->phone,
            // 'finished_order_count' => $this->clientOrders()->whereIn('order_status',['client_finish','driver_finish','admin_finish'])->count(),
            'finished_order_count' => 0,
            'wallet' => $this->wallet,
            'notify_link' => route('dashboard.notification.store'),
            'show_link' => route('dashboard.client.show',$this->id),
            'edit_link' => route('dashboard.client.edit',$this->id),
            'destroy_link' => route('dashboard.client.destroy',$this->id),
            'created_at' => $this->created_at->format("Y-m-d")
        ];
    }
}

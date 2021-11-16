<?php

namespace App\Http\Resources\Dashboard\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class SingleDriverResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $package_type = trans('dashboard.driver.package_types.monthly');
        if (@$this->driver->is_on_default_package) {
            $package_type = trans('dashboard.driver.package_types.on_order');
        }
        return [
            'id' => $this->id,
            'avatar' => $this->avatar,
            'fullname' => $this->fullname,
            'email' => $this->email,
            'phone' => $this->phone,
            'identity_number' => $this->identity_number,
            'finished_order_count' => $this->driverOrders->whereIn('order_status',['client_finish','admin_finish','driver_finish'])->count(),
            'package_type' => $package_type,
            'wallet' => $this->wallet,
            'car_info' => view('dashboard.driver.ajax.car',['driver' => $this])->render(),
            'toggle_data' => view('dashboard.driver.ajax.toggle',['driver' => $this])->render(),
            'driver_type' => view('dashboard.driver.ajax.driver_type',['driver' => $this])->render(),
            'notify_link' => route('dashboard.notification.store'),
            'show_link' => route('dashboard.driver.show',$this->id),
            'edit_link' => route('dashboard.driver.edit',$this->id),
            'destroy_link' => route('dashboard.driver.destroy',$this->id),
            'created_at' => $this->created_at->format("Y-m-d"),
            'end_subscribe_at' => optional(@$this->driver->subscribedPackage->end_at)->format("Y-m-d")
        ];
    }
}

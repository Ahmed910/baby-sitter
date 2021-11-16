<?php

namespace App\Http\Resources\Api\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverPackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $package = json_decode($this->package_data);
        $is_extend = false;
        $extend_duration = 0;
        if (@$this->package->is_extend_active && @$this->package->start_extend_at && now()->isBetween(@$this->package->start_extend_at,@$this->package->end_extend_at)) {
            $extend_duration = $this->package->extend_duration;
            $is_extend = true;
        }
        return [
          'package_id' => (int)$this->package_id,
          'created_at' => $this->created_at->format('Y-m-d'),
          'subscribed_at' => optional($this->subscribed_at)->format("Y-m-d"),
          'end_at' => optional($this->end_at)->format("Y-m-d"),
          'is_paid' => (boolean)$this->is_paid,
          'subscribe_status' => $this->subscribe_status,
          'package_price' => $this->price,
          'is_use_extend' => (bool)$this->is_use_extend,
          'is_extend_active' => (bool)$is_extend,
          'extend_duration' => $extend_duration,
          'is_expired' => now()->gt($this->end_at),
          'is_admin_accept' => auth('api')->user()->driver->is_admin_accept ? true : false,
          'wallet' => (boolean) auth('api')->user()->wallet,
          'package' => new PackageResource($package),
         ];
    }

}

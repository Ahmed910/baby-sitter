<?php

namespace App\Classes;

use App\Http\Resources\Api\Client\Order\SingleOrderResource;
use App\Models\MainOrder;

class OrderStatuses
{
    public function getDetailsForOrder(int $order_id,string $user_id)
    {
        $order = MainOrder::where($user_id,auth('api')->id())->findOrFail($order_id);
        if (isset($order) && $order) {
            return (new SingleOrderResource($order))->additional(['status'=>'success','message'=>'']);
        }
        return response()->json(['data'=>null,'status'=>'fail','message'=>trans('api.messages.id_not_found')],404);
    }

   
}

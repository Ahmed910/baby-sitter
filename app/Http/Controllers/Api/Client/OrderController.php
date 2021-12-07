<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BabySitter\OrderSitterRequest;
use App\Http\Requests\Api\ChildCenter\OrderCenterRequest;
use App\Http\Resources\Api\Client\Order\OrderResource;
use App\Http\Resources\Api\Client\Order\SingleCenterResource;
use App\Http\Resources\Api\Client\Order\SingleSitterOrderResource;
use App\Models\CenterOrder;
use App\Models\MainOrder;
use App\Models\SitterOrder;
use App\Traits\Order;
use Illuminate\Http\Request;

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

    public function getOrders()
    {
        $data=[];
        $current_orders = MainOrder::where('client_id',auth('api')->id())->whereHas('sitter_order',function($q){
            $q->whereIn('status',['pending','waiting','with_the_child']);
        })->orWhereHas('center_order',function($q){
            $q->whereIn('status',['pending','waiting','active']);
        })->get();
        $data['current_orders'] = OrderResource::collection($current_orders);
        $previous_orders = MainOrder::where('client_id',auth('api')->id())->whereHas('sitter_order',function($q){
            $q->whereIn('status',['rejected','canceled','completed']);
        })->orWhereHas('center_order',function($q){
            $q->whereIn('status',['rejected','canceled','completed']);
        })->get();
        $data['previous_orders'] = OrderResource::collection($previous_orders);

        return response()->json(['data'=>$data,'status'=>'success','message'=>'']);
    }

    public function getSitterOrderDetails($sitter_order_id)
    {
        $sitter_order = SitterOrder::where('client_id',auth('api')->id())->findOrFail($sitter_order_id);
        return (new SingleSitterOrderResource($sitter_order))->additional(['status'=>'success','message'=>'']);
    }
    public function getCenterOrderDetails($center_order_id)
    {
        $center_order = CenterOrder::where('client_id',auth('api')->id())->findOrFail($center_order_id);
        return (new SingleCenterResource($center_order))->additional(['status'=>'success','message'=>'']);
    }


}

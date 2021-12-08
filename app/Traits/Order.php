<?php

namespace App\Traits;

use App\Classes\OrderHourCenter;
use App\Classes\OrderHourSitter;
use App\Classes\OrderMonthCenter;
use App\Classes\OrderMonthSitter;
use App\Http\Requests\Api\BabySitter\OrderSitterRequest;
use App\Http\Requests\Api\ChildCenter\OrderCenterRequest;
use App\Models\CenterOrder;
use App\Models\Chat;
use App\Models\MainOrder;
use App\Models\Service;
use App\Models\SitterOrder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait Order
{

    private OrderMonthCenter $month_center;
    private OrderHourCenter $hour_center;
    private OrderMonthSitter $month_sitter;
    private OrderHourSitter $hour_sitter;

    public function __construct(OrderMonthCenter $month_center,OrderHourCenter $hour_center,OrderMonthSitter $month_sitter,OrderHourSitter $hour_sitter)
    {
        $this->month_center = $month_center;
        $this->hour_center = $hour_center;
        $this->month_sitter = $month_sitter;
        $this->hour_sitter = $hour_sitter;
    }



    protected function SitterOrder(OrderSitterRequest $request)
    {

        if($request->pay_type == 'credit' && $request->check_order == 'test'){
            return response()->json(['data'=>null,'status'=>'success','message'=>trans('api.messages.payment_has_been_successfully')],200);
        }
        if($request->pay_type == 'wallet' && $request->price > auth('api')->user()->wallet){
            return response()->json(['data'=>null,'status'=>'fail','message'=>trans('api.messages.your_wallet_does_not_have_enough_balance')]);
        }
        $order_data = ['pay_type','sitter_id','service_id','lat','lng','location','transaction_id','price'];
        DB::beginTransaction();

        try {
             $service = Service::findOrFail($request->service_id);
             $main_order = MainOrder::create(['client_id'=>auth('api')->id(),'sitter_id'=>$request->sitter_id,'to'=>'sitter']);
             if($service->service_type == 'hour'){
                //   $price = $this->calculatePricePerHour($service,$request);

                  $order = SitterOrder::create(array_only($request->validated(),$order_data)+['client_id'=>auth('api')->id(),'main_order_id'=>$main_order->id]);
                  $this->hour_sitter->saveOrderByHourService(array_only($request->validated(), ['date','start_time','end_time']),$order);
             }else{


                //   $price = $this->calculatePricePerMonth($service,$request);

                  $order = SitterOrder::create(array_only($request->validated(),$order_data)+['client_id'=>auth('api')->id(),'main_order_id'=>$main_order->id]);
                  $this->month_sitter->saveOrderByMonthService(array_only($request->validated(), ['start_date','end_date']),$order,$request->schedules);
             }

             $order->kids()->createMany($this->getKids($request->kids,'SitterOrder',$order->id));
             if ($request->pay_type == 'wallet') {
                 $this->updateWalletBalance($request->sitter_id, $request->price);
             }
             $chat = Chat::create(['sender_id' => auth('api')->id() ,'order_id' => $main_order->id ,'receiver_id' => $main_order->sitter_id,'last_message'=>'']);
            DB::commit();
            return response()->json(['data'=>null,'status'=>'success','chat_id'=>$chat->id,'message'=>trans('api.messages.order_created_successfully')]);
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['data'=>null,'status'=>'fail','message'=>trans('api.messages.there_is_an_error_try_again')],400);
        }

    }

    protected function CenterOrder(OrderCenterRequest $request)
    {
        if($request->pay_type == 'credit' && $request->check_order == 'test'){
            return response()->json(['data'=>null,'status'=>'success','message'=>trans('api.messages.payment_has_been_successfully')],200);
        }
        if($request->pay_type == 'wallet' && $request->price > auth('api')->user()->wallet){
            return response()->json(['data'=>null,'status'=>'fail','message'=>trans('api.messages.your_wallet_does_not_have_enough_balance')]);
        }
        $order_data = ['pay_type','center_id','baby_sitter_id','service_id','transaction_id','price'];
        DB::beginTransaction();

        try {


             $service = Service::findOrFail($request->service_id);
             $main_order = MainOrder::create(['client_id'=>auth('api')->id(),'center_id'=>$request->center_id,'to'=>'center']);
             if($service->service_type == 'hour')
             {
                // $price = $this->calculatePricePerHour($service,$request);
                $order = CenterOrder::create(array_only($request->validated(),$order_data)+['client_id'=>auth('api')->id(),'main_order_id'=>$main_order->id]);
                $this->hour_center->saveOrderByHourService(array_only($request->validated(), ['date','start_time','end_time']),$order);

             }else{
                // $price = $this->calculatePricePerMonth($service,$request);

                $order = CenterOrder::create(array_only($request->validated(),$order_data)+['client_id'=>auth('api')->id(),'main_order_id'=>$main_order->id]);
                $this->month_center->saveOrderByMonthService(array_only($request->validated(), ['start_date','end_date']),$order,$request->schedules);
             }

             $order->kids()->createMany($this->getKids($request->kids,'CenterOrder',$order->id));
             if($request->pay_type == 'wallet'){
                $this->updateWalletBalance($request->center_id,$request->price);
             }

            DB::commit();
            return response()->json(['data'=>null,'status'=>'success','message'=>trans('api.messages.order_created_successfully')]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['data'=>null,'status'=>'fail','message'=>trans('api.messages.there_is_an_error_try_again')],400);
        }

    }



    private function updateWalletBalance($provider_id,$price)
    {
        $client = User::findOrFail(auth('api')->id());
        $updated_wallet_for_client = $client->wallet - $price;
        $provider = User::findOrFail($provider_id);
        $updated_wallet_for_provider = $provider->wallet + $price;
        $client->update(['wallet'=>$updated_wallet_for_client]);
        $provider->update(['wallet'=>$updated_wallet_for_provider]);
    }

    // private function calculatePricePerHour($service,$request)
    // {
    //     $hours_num = ((Carbon::parse($request->end_time))->diffInHours((Carbon::parse($request->start_time))));
    //     $service_price = $service->user_services()->where('user_id',$request->sitter_id)->first();
    //     $price = $service_price->price * count($request->kids) * $hours_num;
    //     return $price;
    //    // $order = SitterOrder::create(array_only($request->validated(),$order_data)+['client_id'=>auth('api')->id(),'price'=>$price]);
    // }

    // private function calculatePricePerMonth($service,$request)
    // {
    //     $hours_num = 0 ;

    //     $schedules = $request->schedules;

    //      for($i = 0; $i < count($schedules); $i++)
    //      {
    //        $hours_num+= ((Carbon::parse($schedules[$i]['end_time']))->diffInHours((Carbon::parse($schedules[$i]['start_time']))));
    //      }

    //      $service_price = $service->user_services()->where('user_id',$request->sitter_id)->first();
    //      $price = $service_price->price * count($request->kids) * $hours_num;
    //      return $price;
    // }

    private function getKids($kids,$model,$orderId)
    {

        $data = [];
        foreach($kids as $kid){
            $data[]=[
                'kid_id' => $kid,
                'order_kidsable_type'=>"App/Models/{$model}",
                'order_kidsable_id'=>$orderId
            ];
        }

        return $data;
    }


}

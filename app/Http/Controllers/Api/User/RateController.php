<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Rate\RateRequest;
use App\Http\Resources\Api\User\RateForSpecificOrderResource;
use App\Models\BabySitter;
use App\Models\CenterOrder;
use App\Models\MainOrder;
use App\Models\Rate;
use App\Models\SitterOrder;
use App\Models\User;
use Illuminate\Http\Request;

class RateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RateRequest $request)
    {
        // dd(auth('api')->user()->user_type);

        $order = MainOrder::findOrFail($request->order_id);
        if($order->to == 'sitter')
        {
            $sitter_order = SitterOrder::where('status','completed')->findOrFail(optional($order->sitter_order)->id);
        }else{
            $center_order = CenterOrder::where('status','completed')->findOrFail(optional($order->center_order)->id);
        }
        if(isset($request->to) && $request->to){

            Rate::updateOrCreate(
                ['order_id' => $order->id, 'from'=>auth('api')->id(),'to'=>$request->to],
                ['order_id' => $order->id, 'from'=>auth('api')->id(),
                'to'=>$request->to,'rate'=>$request->rate,
                'review'=>$request->review]
            );

            $rate_avg = Rate::where('to',$request->to)->avg('rate');

            User::findOrFail($request->to)->update(['rate_avg'=>$rate_avg]);
        }elseif(isset($request->to_baby_sitter) && $request->to_baby_sitter){
            Rate::updateOrCreate(
                ['order_id' => $order->id, 'from'=>auth('api')->id(),'to_baby_sitter'=>$request->to_baby_sitter],
                ['order_id' => $order->id, 'from'=>auth('api')->id(),
                'to_baby_sitter'=>$request->to_baby_sitter,'rate'=>$request->rate,
                'review'=>$request->review]
            );

            $rate_avg = Rate::where('to_baby_sitter',$request->to_baby_sitter)->avg('rate');
            BabySitter::findOrFail($request->to_baby_sitter)->update(['rate_avg'=>$rate_avg]);
        }

        return response()->json(['data'=>null,'status'=>'success','message'=>trans('api.messages.successfully_evaluated')]);
    }

    public function getRateForMeOnOrder($order_id)
    {
     $rate = Rate::where(['from'=>auth('api')->id(),'order_id'=>$order_id])->first();
     return (new RateForSpecificOrderResource($rate))->additional(['status'=>'success','message'=>'']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

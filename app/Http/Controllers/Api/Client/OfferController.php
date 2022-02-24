<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\Offers\ApplyOfferRequest;
use App\Http\Resources\Api\Offers\OfferResource;
use App\Models\{MainOrder,Offer};

class OfferController extends Controller
{

    public function applyOffer(ApplyOfferRequest $request)
    {
        // dd('dddddd');
        $offer = Offer::where(['promo_code'=>$request->promo_code,'user_id'=>$request->user_id,'status'=>'active'])->where('end_date','>=',now()->format('Y-m-d'))->firstOrFail();

        $orders_with_that_offer = MainOrder::where('offer_id',$offer->id)->count();
        if($orders_with_that_offer <= $offer->max_num)
        {
           return (new OfferResource($offer))->additional(['status'=>'success','message'=>trans('api.messages.offer_has_been_applied_successfully')]);
        }
        return response()->json(['data'=>null,'status'=>'fail','message'=>trans('api.messages.the_max_num_for_offer_has_been_used')],401);
    }
}

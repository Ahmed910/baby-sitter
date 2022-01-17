<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\Offers\ApplyOfferRequest;
use App\Models\MainOrder;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function applyOffer(ApplyOfferRequest $request)
    {
        $offer = Offer::where('promo_code',$request->promo_code)->where('end_date','>=',now()->format('Y-m-d'))->firstOrFail();
        $financials_data = [];
        $orders_with_that_offer = MainOrder::where('offer_id',$offer->id)->count();
        if($orders_with_that_offer <= $offer->max_num)
        {
           $financials_data['discount'] = $offer->discount;
           $financials_data['total_price_before_discount'] = (float)$request->order_price;
           $financials_data['total_price_after_discount'] = $financials_data['total_price_before_discount'] - $financials_data['discount'];
           return response()->json(['financial_data'=>$financials_data,'status'=>'success','message'=>'']);

        }
        return response()->json(['data'=>null,'status'=>'fail','message'=>trans('api.messages.the_max_num_for_offer_has_been_used')],401);
    }
}

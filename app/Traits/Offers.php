<?php

namespace App\Traits;

use App\Http\Requests\Api\BabySitter\Offers\OfferRequest;
use App\Http\Requests\Api\BabySitter\Offers\PayOfferRequest;
use App\Http\Resources\Api\Offers\OfferResource;
use App\Http\Resources\Api\Offers\SingleOfferResource;
use App\Models\MainOrder;
use App\Models\Offer;

trait Offers{

   use Order;
    protected function getOffers()
    {
        $offers = Offer::offeruser()->latest()->paginate(50);
        return OfferResource::collection($offers)->additional(['status'=>'success','message'=>'']);
    }

    protected function getSingleOffer($id)
    {
        $offer = Offer::offeruser()->findOrFail($id);
        return (new SingleOfferResource($offer))->additional(['status'=>'success','message'=>'']);
    }

    protected function CreateOffer(OfferRequest $request)
    {
        $user = auth('api')->user();

        Offer::create($request->validated()+['user_id'=>$user->id,'user_type'=>$user->user_type]);
        return response()->json(['data'=>null,'status'=>'success','message'=>trans('api.messages.offer_created_successfully')]);
    }

    protected function pay(PayOfferRequest $request,$id)
    {
        $user = auth('api')->user();
        $offer = Offer::where(['status'=>'accepted','user_id'=>$user->id])->findOrFail($id);
        if ($request->pay_type == 'wallet') {
            if($request->offer_fees > $user->wallet){

                return response()->json(['data' => null, 'status' => 'fail', 'message' => trans('api.messages.your_wallet_does_not_have_enough_balance')]);
            }else{
                $this->withdrawFromWallet($request->offer_fees,$user->id);
            }
        }

        $offer->update($request->validated()+['status'=>'active']);
        return (new SingleOfferResource($offer))->additional(['status'=>'success','message'=>trans('api.messages.offer_has_been_activated')]);
        // return response()->json(['data'=>null,'status'=>'success','message'=>trans('api.messages.offer_has_been_activated')]);
    }

    protected function inactiveForOffer($id)
    {
        $num_of_used = MainOrder::where('offer_id',$id)->count();
        $offer = Offer::offeruser()->where('status','active')->where('end_date','>',now()->format('Y-m-d'))
        ->findOrFail($id);
        if($num_of_used < $offer->max_num){
            $offer->update(['status'=>'inactive']);
            
            return (new SingleOfferResource($offer))->additional(['status'=>'success','message'=>trans('api.messages.offer_has_been_inactive')]);
        }
        return response()->json(['data'=>null,'status'=>'fail','message'=>trans('api.messages.cant_make_offer_inacitve_while_its_status_invalid')],400);
    }

    protected function reactiveForOffer(OfferRequest $request,$id)
    {
        $num_of_used = MainOrder::where('offer_id',$id)->count();
        $offer = Offer::offeruser()->findOrFail($id);
        if($offer->end_date < now()->format('Y-m-d') || $offer->status =='inactive' || $offer->max_num == $num_of_used){

            $offer->update($request->validated()+['status'=>'pending']);
            return response()->json(['data'=>null,'status'=>'success','message'=>trans('api.messages.offer_request_has_been_sent_management_to_reactive_offer')]);
        }
        return response()->json(['data'=>null,'status'=>'success','message'=>trans('api.messages.cant_send_request')],402);

        // $offer->refresh();
    }

    protected function UpdateOffer(OfferRequest $request,$id)
    {

        $offer = Offer::offeruser()->where('end_date','<',now())->where('status','active')->findOrFail($id);

        $offer->update($request->validated());
        return response()->json(['data'=>null,'status'=>'success','message'=>trans('api.messages.offer_has_been_updated')]);
    }
}

<?php

namespace App\Traits;

use App\Http\Requests\Api\BabySitter\Offers\OfferRequest;
use App\Http\Resources\Api\Driver\OfferResource;
use App\Http\Resources\Api\Offers\OfferResource as OffersOfferResource;
use App\Http\Resources\Api\Offers\SingleOfferResource;
use App\Models\Offer;

trait Offers{


    protected function getOffers()
    {
        $offers = Offer::offeruser()->latest()->paginate(50);
        return OffersOfferResource::collection($offers)->additional(['status'=>'success','message'=>'']);
    }

    protected function getSingleOffer($id)
    {
        $offer = Offer::offeruser()->findOrFail($id);
        return (new SingleOfferResource($offer))->additional(['status'=>'success','message'=>'']);
    }

    protected function CreateOffer(OfferRequest $request)
    {
        Offer::create($request->validated()+['user_id'=>auth('api')->id()]);
        return response()->json(['data'=>null,'status'=>'success','message'=>trans('api.messages.offer_created_successfully')]);
    }

    protected function UpdateOffer(OfferRequest $request,$id)
    {
        $offer = Offer::offeruser()->findOrFail($id);
        $offer->update($request->validated());
        return response()->json(['data'=>null,'status'=>'success','message'=>trans('api.messages.offer_updated_successfully')]);
    }
}
?>

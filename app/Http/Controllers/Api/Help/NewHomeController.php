<?php

namespace App\Http\Controllers\Api\Help;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Help\CityRequest;
use App\Http\Resources\Api\Offers\OfferResource;
use App\Http\Resources\Api\User\CenterInfoResource;
use App\Http\Resources\Api\User\SitterInfoResource;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Http\Request;

class NewHomeController extends Controller
{
    public function getSitters(CityRequest $request)
    {
        $sitters = User::when($request->city_id,function($q) use($request){
             $q->whereHas('profile',function($q) use($request){
                $q->where('city_id',$request->city_id);
             });
        })->where('user_type','babysitter')->get();

        return SitterInfoResource::collection($sitters)->additional(['status'=>'success','message'=>'']);
    }

    public function getCenters(CityRequest $request)
    {
        $centers = User::when($request->city_id,function($q) use($request){
            $q->whereHas('profile',function($q) use($request){
               $q->where('city_id',$request->city_id);
            });
       })->where('user_type','childcenter')->get();
       return CenterInfoResource::collection($centers)->additional(['status'=>'success','message'=>'']);
    }

    public function getAllOffers()
    {
        $offers = Offer::latest()->get();
        return OfferResource::collection($offers)->additional(['status'=>'success','message'=>'']);
    }


}

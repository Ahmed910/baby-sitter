<?php

namespace App\Http\Controllers\Api\Help;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Help\CityRequest;
use App\Http\Requests\Api\Help\FilterCentersRequest;
use App\Http\Requests\Api\Help\FilterSittersRequest;
use App\Http\Requests\Api\Help\NearestCentersRequest;
use App\Http\Resources\Api\Babysitters\BabySitterResource;
use App\Http\Resources\Api\Center\CenterResource;
use App\Http\Resources\Api\Offers\OfferResource;
use App\Http\Resources\Api\User\CenterInfoResource;
use App\Http\Resources\Api\User\SitterForCenterResource;
use App\Http\Resources\Api\User\SitterInfoResource;
use App\Models\BabySitter;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Http\Request;

class NewHomeController extends Controller
{
    public function getSitters(FilterSittersRequest $request)
    {
        // dd($request->name);
        $sitters = User::when($request->city_id,function($q) use($request){
             $q->whereHas('profile',function($q) use($request){
                $q->where('city_id',$request->city_id);
             });
        })->when($request->name,function($q) use($request){
            $q->where('name','like',"%{$request->name}%");
        })->
        when($request->lowest_price && $request->high_price,function($q) use($request){
            $q->whereHas('user_services',function($query) use($request){
                $query->whereBetween('price',[$request->lowest_price,$request->high_price]);
            });
        })->when($request->high_rate && $request->high_rate == true,function($q)use($request){
            $q->orderByDesc('rate_avg');
        })->sitter()->get();



        return SitterInfoResource::collection($sitters)->additional(['status'=>'success','message'=>'']);
    }

    public function getCenters(FilterCentersRequest $request)
    {
        $centers = User::when($request->city_id,function($q) use($request){
            $q->whereHas('profile',function($q) use($request){
               $q->where('city_id',$request->city_id);
            });
       })->when($request->name,function($q) use($request){
             $q->where('name','like',"%{$request->name}%");
          })->
          when($request->lowest_price && $request->high_price,function($q) use($request){
            $q->whereHas('user_services',function($query) use($request){
                $query->whereBetween('price',[$request->lowest_price,$request->high_price]);
            });
        })->when($request->high_rate && $request->high_rate == true,function($q)use($request){
           $q->orderByDesc('rate_avg');
        })->when($request->is_educational && $request->is_educational == true,function($q)use($request){
           $q->whereHas('child_centre',function($query) use($request){
               $query->where('is_educational',1);
           });
          })->when(( $request->lat && $request->lng ),function($q) use($request){

              $q->nearest($request->lat,$request->lng);
          })->center()
          ->get();
       return CenterInfoResource::collection($centers)->additional(['status'=>'success','message'=>'']);
    }


    public function getSitterDetails($sitter_id)
    {
        $sitter = User::sitter()->findOrFail($sitter_id);
        return (new BabySitterResource($sitter))->additional(['status'=>'success','message'=>'']);
    }

    public function getCenterDetails($center_id)
    {
        $center = User::center()->findOrFail($center_id);
        return (new CenterResource($center))->additional(['status'=>'success','message'=>'']);
    }
    public function getAllOffers()
    {
        $offers = Offer::latest()->get();
        return OfferResource::collection($offers)->additional(['status'=>'success','message'=>'']);
    }

    public function getNearestCenters(NearestCentersRequest $request)
    {
        $centers = User::when($request->lat && $request->lng, function ($q) use ($request) {
            $q->nearest($request->lat, $request->lng);
    })->get();

        return CenterInfoResource::collection($centers)->additional(['status'=>'success','message'=>'']);
    }


    public function getSitterInfoForCenter($sitter_id)
    {
        $baby_sitter = BabySitter::findOrFail($sitter_id);
        return (new SitterForCenterResource($baby_sitter))->additional(['status'=>'success','message'=>'']);
    }



}

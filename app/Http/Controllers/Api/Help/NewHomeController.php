<?php

namespace App\Http\Controllers\Api\Help;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Help\CityRequest;
use App\Http\Requests\Api\Help\FilterCentersRequest;
use App\Http\Requests\Api\Help\FilterSittersRequest;
use App\Http\Requests\Api\Help\NearestCentersRequest;
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
        // dd($request->name);
        $sitters = User::when($request->city_id,function($q) use($request){
             $q->whereHas('profile',function($q) use($request){
                $q->where('city_id',$request->city_id);
             });
        })->when($request->name,function($q) use($request){
            $q->where('name','like',"%{$request->name}%");
        })->where('user_type','babysitter')->get();


        return SitterInfoResource::collection($sitters)->additional(['status'=>'success','message'=>'']);
    }

    public function getCenters(CityRequest $request)
    {
        $centers = User::when($request->city_id,function($q) use($request){
            $q->whereHas('profile',function($q) use($request){
               $q->where('city_id',$request->city_id);
            });
       })->when($request->name,function($q) use($request){
             $q->where('name','like',"%{$request->name}%");
          })->where('user_type','childcenter')->get();
       return CenterInfoResource::collection($centers)->additional(['status'=>'success','message'=>'']);
    }

    public function getAllOffers()
    {
        $offers = Offer::latest()->get();
        return OfferResource::collection($offers)->additional(['status'=>'success','message'=>'']);
    }

    public function getNearestCenters(NearestCentersRequest $request)
    {
        $centers = User::nearest($request->lat,$request->lng)->get();
        return CenterInfoResource::collection($centers)->additional(['status'=>'success','message'=>'']);
    }

    public function filterSitters(FilterSittersRequest $request)
    {
            //  dd($request->high_rate == true);
         $sitters = User::when($request->lowest_price && $request->high_price,function($q) use($request){
             $q->whereHas('user_services',function($query) use($request){
                 $query->whereBetween('price',[$request->lowest_price,$request->high_price]);
             });
         })->when($request->high_rate && $request->high_rate == true,function($q)use($request){
             $q->whereBetween('rate_avg',[3,5]);
         })->sitter()->get();
         return SitterInfoResource::collection($sitters)->additional(['status'=>'success','message'=>'']);
    }


    public function filterCenters(FilterCentersRequest $request)
    {
            //  dd($request->high_rate == true);
         $centers = User::when($request->lowest_price && $request->high_price,function($q) use($request){
             $q->whereHas('user_services',function($query) use($request){
                 $query->whereBetween('price',[$request->lowest_price,$request->high_price]);
             });
         })->when($request->high_rate && $request->high_rate == true,function($q)use($request){
             $q->whereBetween('rate_avg',[3,5]);
         })->when($request->is_educational && $request->is_educational == true,function($q)use($request){
            $q->whereHas('child_centre',function($query) use($request){
                $query->where('is_educational',1);
            });
           })
         ->center()->get();
         return CenterInfoResource::collection($centers)->additional(['status'=>'success','message'=>'']);
    }


}

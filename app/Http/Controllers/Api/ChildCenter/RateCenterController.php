<?php

namespace App\Http\Controllers\Api\ChildCenter;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\User\RateFromUserResource;
use App\Models\Rate;
use Illuminate\Http\Request;

class RateCenterController extends Controller
{
    public function getRatesForCenter()
    {

        $reviews = Rate::where('to_center',auth('api')->id())->get();
        return RateFromUserResource::collection($reviews)->additional(['status'=>'success','message'=>'']);
    }
}

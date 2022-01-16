<?php

namespace App\Http\Controllers\Api\BabySitter;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\User\RateFromUserResource;
use App\Models\Rate;
use Illuminate\Http\Request;

class RateSitterController extends Controller
{
    public function getRatesForSitter()
    {
     
        $reviews = Rate::where('to',auth('api')->id())->get();
        return RateFromUserResource::collection($reviews)->additional(['status'=>'success','message'=>'']);
    }
}

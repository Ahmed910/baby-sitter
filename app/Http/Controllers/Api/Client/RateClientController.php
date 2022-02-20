<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\User\RateFromUserResource;
use App\Models\Rate;
use Illuminate\Http\Request;

class RateClientController extends Controller
{
    public function index()
    {
        $reviews = Rate::where('to_client',auth('api')->id())->get();
        return RateFromUserResource::collection($reviews)->additional(['status'=>'success','message'=>'']);
    }
}

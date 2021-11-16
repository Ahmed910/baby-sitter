<?php

namespace App\Http\Controllers\Api\Help;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Help\{SliderResource};
use App\Models\{Slider};

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sliders = Slider::active()->latest()->get();
        return SliderResource::collection($sliders)->additional(['status' => 'success','message'=>'']);
    }


}

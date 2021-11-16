<?php

namespace App\Http\Controllers\Api\Help;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Help\AvailableDayResource;
use App\Http\Resources\Api\Help\CarTypeResource;
use App\Http\Resources\Api\Help\Categories\MainCategoryResource;
use App\Http\Resources\Api\Help\Categories\SecondSubCategoryResource;
use App\Http\Resources\Api\Help\Categories\SubCategoryResource;
use App\Http\Resources\Api\Help\DistrictResource;
use App\Http\Resources\Api\Help\SelenderResource;
use App\Http\Resources\Api\Help\FavoriteTimeResource;

use App\Models\AvailableDay;
use App\Models\CarType;
use App\Models\District;
use App\Models\FavoriteTime;
use App\Models\MainCategory;
use App\Models\SecondCategory;
use App\Models\Selender;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    public function getCarTypes()
    {
        $car_types = CarType::latest()->get();
        return CarTypeResource::collection($car_types)->additional(['status'=>'success','message'=>'']);
    }


    public function getSelenders()
    {
        $selenders = Selender::latest()->get();
        return SelenderResource::collection($selenders)->additional(['status'=>'success','message'=>'']);
    }
    public function getDistricts()
    {
        $districts = District::latest()->get();
        return DistrictResource::collection($districts)->additional(['status'=>'success','message'=>'']);
    }
    public function getAvailableDays($district_id)
    {
        $available_days = AvailableDay::where('district_id',$district_id)->latest()->get();
        return AvailableDayResource::collection($available_days)->additional(['status'=>'success','message'=>'']);
    }

    public function getFavoriteTimes($available_day_id)
    {
        $favorite_times = FavoriteTime::where('available_day_id',$available_day_id)->latest()->get();
        return FavoriteTimeResource::collection($favorite_times)->additional(['status'=>'success','message'=>'']);
    }

    public function getMainCategories()
    {
        $main_categories = MainCategory::where('has_sub_category',false)->latest()->get();
        return MainCategoryResource::collection($main_categories)->additional(['status'=>'success','message'=>'']);

    }
    public function getSubCategories()
    {
        $sub_categories = SubCategory::with('mainCategory')->where('has_sub_category',false)->latest()->get();
        return SubCategoryResource::collection($sub_categories)->additional(['status'=>'success','message'=>'']);
    }
    public function getSecondSubCategories()
    {
        $second_sub_categories = SecondCategory::with('subCategory')->latest()->get();
        return SecondSubCategoryResource::collection($second_sub_categories)->additional(['status'=>'success','message'=>'']);
    }
}

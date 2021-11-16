<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FavoriteTimes\FavoriteTimeRequest;
use App\Models\AvailableDay;
use App\Models\District;
use App\Models\FavoriteTime;
use Illuminate\Http\Request;

class FavoriteTimeController extends Controller
{
    public function index()
    {
        $favorite_times = FavoriteTime::latest()->paginate(50);
        return view('dashboard.favorite_time.index',compact('favorite_times'));
    }

    public function create()
    {
        if (!request()->ajax()) {

            $data['available_days'] = AvailableDay::get();
            $data['districts'] = District::get()->pluck('name','id');
            // dd($data['available_days']);
            return view('dashboard.favorite_time.create',$data);
        }
    }

    public function store(FavoriteTimeRequest $request)
    {
        FavoriteTime::create($request->validated());
        return redirect(route('dashboard.favorite_time.index'))->withTrue(trans('dashboard.messages.success_add'));
    }


    public function edit(FavoriteTime $favorite_time)
    {
        if (!request()->ajax()) {
            $data['favorite_time'] = $favorite_time;
            $data['available_days'] = AvailableDay::where('district_id',$favorite_time->district_id)->get();
           
            $data['districts'] = District::get()->pluck('name','id');
            // dd($data['available_days']);
            return view('dashboard.favorite_time.edit',$data);
        }
    }


    public function update(FavoriteTimeRequest $request,FavoriteTime $favorite_time)
    {
        $favorite_time->update($request->validated());
        return redirect(route('dashboard.favorite_time.index'))->withTrue(trans('dashboard.messages.success_update'));
    }

    public function destroy(FavoriteTime $favorite_time)
    {
        if ($favorite_time->delete()) {
          return response()->json(['value' => 1]);
        }
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\{City , Country};
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\City\{CityRequest};

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->ajax()) {
          $cities = City::latest()->paginate(100);
          return view('dashboard.city.index',compact('cities'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
      if (!request()->ajax()) {
          if ($request->country_id) {
              $data['country_id'] = $request->country_id;
          }
          $data['countries'] = Country::get()->pluck('name','id');
          return view('dashboard.city.create',$data);
      }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CityRequest $request)
    {
        if (!request()->ajax()) {
           City::create(array_except($request->validated(),['image']));
           return redirect(route('dashboard.city.index'))->withTrue(trans('dashboard.messages.success_add'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        if (!request()->ajax()) {
           return view('dashboard.city.show',compact('city'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function edit(City $city)
    {
        if (!request()->ajax()) {
            $countries =Country::get()->pluck('name','id');
            return view('dashboard.city.edit',compact('countries','city'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function update(CityRequest $request, City $city)
    {
        if (!request()->ajax()) {
           $city->update(array_except($request->validated(),['image']));
           return redirect(route('dashboard.city.index'))->withTrue(trans('dashboard.messages.success_update'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        if ($city->delete()) {
          return response()->json(['value' => 1]);
        }
    }
}

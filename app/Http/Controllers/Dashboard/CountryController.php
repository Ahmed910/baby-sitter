<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Country\CountryRequest;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->ajax()) {
          $countries = Country::latest()->paginate(100);
          return view('dashboard.country.index',compact('countries'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      if (!request()->ajax()) {
          return view('dashboard.country.create');
      }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CountryRequest $request)
    {
        if (!request()->ajax()) {
           Country::create(array_except($request->validated(),['image']));
           return redirect(route('dashboard.country.index'))->withTrue(trans('dashboard.messages.success_add'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function show(Country $country)
    {
        if (!request()->ajax()) {
           return view('dashboard.country.show',compact('country'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country)
    {
        if (!request()->ajax()) {
            $countries = Country::get()->pluck('name','id');
            return view('dashboard.country.edit',compact('countries','country'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function update(CountryRequest $request, Country $country)
    {
        if (!request()->ajax()) {
           $country->update(array_except($request->validated(),['image']));
           return redirect(route('dashboard.country.index'))->withTrue(trans('dashboard.messages.success_update'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {
        if ($country->delete()) {
          return response()->json(['value' => 1]);
        }
    }
}

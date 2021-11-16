<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\{City , Country};
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Profile\{UpdatePasswordRequest,ProfileRequest};

class ProfileController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
      if (!request()->ajax()) {
          $data['countries'] = Country::get()->pluck('name','id');
          $data['cities'] = City::get()->pluck('name','id');
          return view('dashboard.profile.profile',$data);
      }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProfileRequest $request)
    {
        if (!request()->ajax()) {
           auth()->user()->update(array_except($request->validated(),['country_id','city_id']));
           auth()->user()->profile()->updateOrCreate(['user_id' => auth()->id()],array_only($request->validated(),['country_id','city_id']));
           return back()->withTrue(trans('dashboard.messages.success_update'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        if (!request()->ajax()) {
           auth()->user()->update(array_except($request->validated(),['old_password']));
           return back()->withTrue(trans('dashboard.messages.success_update'));
        }
    }
}

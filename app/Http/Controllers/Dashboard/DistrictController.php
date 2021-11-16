<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\District\DistrictRequest;
use App\Models\City;
use App\Models\District;
use CurrentLang;
use Illuminate\Http\Request;

class DistrictController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->ajax()) {
            $districts = District::latest()->paginate(100);
            return view('dashboard.district.index',compact('districts'));
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
            return view('dashboard.district.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DistrictRequest $request)
    {
        if (!request()->ajax()) {
            District::create($request->validated());
            return redirect(route('dashboard.district.index'))->withTrue(trans('dashboard.messages.success_add'));
         }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(District $district)
    {
        if (!request()->ajax()) {

            return view('dashboard.district.edit',compact('district'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DistrictRequest $request, District $district)
    {
        if (!request()->ajax()) {
            $district->update($request->validated());
            return redirect(route('dashboard.district.index'))->withTrue(trans('dashboard.messages.success_update'));
         }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(District $district)
    {
        if ($district->delete()) {
            return response()->json(['value' => 1]);
          }
    }
}

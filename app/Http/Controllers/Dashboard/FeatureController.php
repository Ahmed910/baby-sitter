<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Feature\FeatureRequest;
use App\Models\Feature;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->ajax()) {
            $features = Feature::latest()->paginate(100);
            return view('dashboard.feature.index',compact('features'));
          }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.feature.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

        public function store(FeatureRequest $request)
        {
            if (!request()->ajax()) {
                Feature::create($request->validated());
                return redirect(route('dashboard.feature.index'))->withTrue(trans('dashboard.messages.success_add'));
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
    public function edit(Feature $feature)
    {
        if (!request()->ajax()) {
            return view('dashboard.feature.edit',compact('feature'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FeatureRequest $request, Feature $feature)
    {
        if (!request()->ajax()) {
            $feature->update($request->validated());
            return redirect(route('dashboard.feature.index'))->withTrue(trans('dashboard.messages.success_update'));
         }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Feature $feature)
    {
        if ($feature->delete()) {
            return response()->json(['value' => 1]);
          }
    }
}

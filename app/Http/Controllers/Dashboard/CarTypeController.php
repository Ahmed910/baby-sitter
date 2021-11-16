<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\CarType\CarTypeRequest;
use App\Models\CarType;
use Illuminate\Http\Request;

class CarTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $car_types = CarType::latest()->paginate(50);
        return view('dashboard.car_type.index',compact('car_types'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!request()->ajax()) {
            return view('dashboard.car_type.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CarTypeRequest $request)
    {
        CarType::create($request->validated());
        return redirect(route('dashboard.car_type.index'))->withTrue(trans('dashboard.messages.success_add'));
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(CarType $car_type)
    {
        return view('dashboard.car_type.edit',compact('car_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CarTypeRequest $request, CarType $car_type)
    {
        $car_type->update($request->validated());
        return redirect(route('dashboard.car_type.index'))->withTrue(trans('dashboard.messages.success_update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CarType $car_type)
    {
         if ($car_type->delete()) {
            return response()->json(['value' => 1]);
        }
    }
}

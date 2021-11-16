<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\AvailableDay\AvailableDayRequest;
use App\Models\AvailableDay;
use App\Models\District;
use Illuminate\Http\Request;

class AvailableDayController extends Controller
{

    public function index()
    {
        $available_days = AvailableDay::latest()->paginate(50);
        return view('dashboard.available_day.index',compact('available_days'));
    }
     public function create()
    {
        if (!request()->ajax()) {

            $data['districts'] = District::get()->pluck('name','id');
            return view('dashboard.available_day.create',$data);
        }
    }

    public function store(AvailableDayRequest $request)
    {

        AvailableDay::create($request->validated());
        return redirect(route('dashboard.available_day.index'))->withTrue(trans('dashboard.messages.success_add'));
    }

    public function edit(AvailableDay $available_day)
    {
        if (!request()->ajax()) {
            $data['available_day'] = $available_day;
            $data['districts'] = District::get()->pluck('name','id');
            return view('dashboard.available_day.edit',$data);
        }
    }

    public function update(AvailableDayRequest $request,AvailableDay $available_day)
    {

        $available_day->update($request->validated());
        return redirect(route('dashboard.available_day.index'))->withTrue(trans('dashboard.messages.success_update'));
    }

    public function destroy(AvailableDay $available_day)
    {
        if ($available_day->delete()) {
          return response()->json(['value' => 1]);
        }
    }
}

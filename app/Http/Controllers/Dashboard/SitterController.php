<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Sitter\SitterRequest;
use App\Models\City;
use App\Models\Feature;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use DB;

class SitterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->ajax()) {
        $sitters = User::where('user_type','babysitter')->paginate(100);
        return view('dashboard.sitter.index',compact('sitters'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $services = Service::get();
        $features = Feature::get()->pluck('name', 'id');
        $cities = City::get()->pluck('name', 'id');
        return view('dashboard.sitter.create', compact('services', 'cities', 'features'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SitterRequest $request)
    {
        $profile_data = ['country_id', 'city_id'];
        //  dd($request->services);
        $services=[];
        foreach ($request->services as $service) {
            if(isset($service['service_id'])){
                $services[$service['service_id']]=['price'=>$service['price']];
            }
        }
        $features = $request->features;

        DB::beginTransaction();

        try {
            $user = User::create(array_except($request->validated(),$profile_data)+['user_type'=>'babysitter','referral_code' => generate_unique_code(8,'\\App\\Models\\User','referral_code','alpha_numbers','lower')]);

            $user->profile()->create(array_only($request->validated(),$profile_data)+['added_by_id' => auth('api')->id()]);
            $user->services()->sync($services);
            $user->features()->sync($features);
            DB::commit();
            return redirect(route('dashboard.sitter.index'))->withTrue(trans('dashboard.messages.success_add'));
        } catch (\Exception $e) {
            DB::rollback();
            return redirect(route('dashboard.sitter.index'))->withFalse(trans('dashboard.messages.something_went_wrong_please_try_again'));
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
    public function edit($id)
    {
        $sitter = User::where('user_type','babysitter')->findOrFail($id);
        $services = Service::get();
        $features = Feature::get()->pluck('name', 'id');
        $cities = City::get()->pluck('name', 'id');
        return view('dashboard.sitter.edit', compact('sitter','services', 'cities', 'features'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SitterRequest $request, $id)
    {
        $profile_data = ['country_id', 'city_id'];
        //  dd(array_only($request->validated(),$profile_data));
        $services=[];
        foreach ($request->services as $service) {
            if(isset($service['service_id'])){
                $services[$service['service_id']]=['price'=>$service['price']];
            }
        }
        $features = $request->features;
        $user=User::findOrFail($id);
        DB::beginTransaction();

        try {
            $user->update(array_except($request->validated(),array_merge($profile_data,$features,$services)));

            $user->profile()->update(array_only($request->validated(),$profile_data));
            $user->services()->sync($services);
            $user->features()->sync($features);
            DB::commit();
            return redirect(route('dashboard.client.index'))->withTrue(trans('dashboard.messages.success_update'));
        } catch (\Exception $e) {
            DB::rollback();
            return redirect(route('dashboard.sitter.index'))->withFalse(trans('dashboard.messages.something_went_wrong_please_try_again'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sitter = User::where('user_type','babysitter')->findOrFail($id);
        if ($sitter->delete()) {
            return response()->json(['value' => 1]);
        }
    }
}

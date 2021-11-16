<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\{ User , Role };
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\User\ManagerRequest;

class ManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->ajax()) {
          $managers = User::where('id',"<>",auth()->id())->where('user_type' , 'admin')->latest()->paginate(100);
          return view('dashboard.manager.index',compact('managers'));
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
          $roles = Role::get()->pluck('name','id');
          return view('dashboard.manager.create',compact('roles'));
      }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ManagerRequest $request)
    {
        if (!request()->ajax()) {
            \DB::beginTransaction();
            try {
                $manager = User::create($request->validated()+['user_type' => 'admin']);
                $manager->profile()->create(array_only($request->validated(),['country_id','city_id'])+['added_by_id' => auth()->id()]);
                \DB::commit();
                return redirect(route('dashboard.manager.index'))->withTrue(trans('dashboard.messages.success_add'));
             }catch (\Exception $e) {
                 \DB::rollback();
                 return redirect(route('dashboard.manager.index'))->withFalse(trans('dashboard.messages.something_went_wrong_please_try_again'));
             }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $manager
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!request()->ajax()) {

            $manager = User::where('id',"<>",auth()->id())->findOrFail($id);
            return view('dashboard.manager.show',compact('manager'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $manager
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!request()->ajax()) {
            $roles = Role::get()->pluck('name','id');
            $manager = User::where('id',"<>",auth()->id())->findOrFail($id);
            return view('dashboard.manager.edit',compact('manager','roles'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $manager
     * @return \Illuminate\Http\Response
     */
    public function update(ManagerRequest $request, $id)
    {
        if (!request()->ajax()) {
            \DB::beginTransaction();
            try {
                $manager = User::where('id',"<>",auth()->id())->findOrFail($id);
                $manager->update($request->validated()+['user_type' => 'admin']);
                $manager->profile()->updateOrCreate(['user_id' => $manager->id],array_only($request->validated(),['country_id','city_id']));
                \DB::commit();
                return redirect(route('dashboard.manager.index'))->withTrue(trans('dashboard.messages.success_update'));
            }catch (\Exception $e) {
                \DB::rollback();
                return redirect(route('dashboard.manager.index'))->withFalse(trans('dashboard.messages.something_went_wrong_please_try_again'));
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $manager
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $manager = User::where('id',"<>",auth()->id())->findOrFail($id);
        if ($manager->delete()) {
          return response()->json(['value' => 1]);
        }
    }
}

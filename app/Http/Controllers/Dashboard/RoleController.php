<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\{ Role , Permission };
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Role\RoleRequest;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->ajax()) {
          $roles = Role::latest()->paginate(100);
          return view('dashboard.role.index',compact('roles'));
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
          $route=[];
            foreach (app()->routes->getRoutes() as $value) {
                    $locale = app()->getLocale();
                    if($value->getPrefix() == $locale."/dashboard" || $value->getPrefix() == "/".$locale."/dashboard"){
                        if($value->getName() != 'dashboard.' && !is_null($value->getName())){
                            $route[]= str_before(str_after($value->getName(),'.'),'.') ;
                        }elseif (is_null($value->getName())) {
                            $route[]= 'home' ;

                        }
                    }
                   }

          $routes = array_values(array_unique($route));
          $public_routes = ['login' , 'post_login' , 'post_login' , 'seenNotify' , 'logout' , 'notification' , 'profile'];
          return view('dashboard.role.create',compact('routes','public_routes'));
      }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        if (!request()->ajax()) {
            $permission_inputs =$request->validated()['permissions'];
            $role_inputs = array_only($request->validated(),config('translatable.locales'));
            $role = Role::create($role_inputs);
            $permission_list = [];
            foreach ($permission_inputs as $permission) {
                foreach ($permission as $singlePermission) {
                    $permission_obj= Permission::updateOrCreate(['route_name' => $singlePermission['route_name']],$singlePermission);
                    $permission_list[] =$permission_obj->id;
                }
            }
            $role->permissions()->sync($permission_list);
            return redirect(route('dashboard.role.index'))->withTrue(trans('dashboard.messages.success_add'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        if (!request()->ajax()) {
           return view('dashboard.role.show',compact('role'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        if (!request()->ajax()) {
            $route=[];
              foreach (app()->routes->getRoutes() as $value) {
                      $locale = app()->getLocale();
                      if($value->getPrefix() == $locale."/dashboard" || $value->getPrefix() == "/".$locale."/dashboard"){
                          if($value->getName() != 'dashboard.' && !is_null($value->getName())){
                              $route[]= str_before(str_after($value->getName(),'.'),'.') ;
                          }elseif (is_null($value->getName())) {
                              $route[]= 'home' ;

                          }
                      }
                     }
            $routes = array_values(array_unique($route));
            $public_routes = ['login' , 'post_login' , 'post_login' , 'seenNotify' , 'logout' , 'notification' , 'profile'];
            return view('dashboard.role.edit',compact('role','routes','public_routes'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, Role $role)
    {
        if (!request()->ajax()) {
            $permission_inputs =$request->validated()['permissions'];
            $role_inputs = array_only($request->validated(),config('translatable.locales'));
            $role->update($role_inputs);
            $permission_list = [];
            foreach ($permission_inputs as $permission) {
                foreach ($permission as $singlePermission) {
                    $permission_obj= Permission::updateOrCreate(['route_name' => $singlePermission['route_name']],$singlePermission);
                    $permission_list[] =$permission_obj->id;
                }
            }
            $role->permissions()->sync($permission_list);
            return redirect(route('dashboard.role.index'))->withTrue(trans('dashboard.messages.success_update'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        if ($role->delete()) {
          return response()->json(['value' => 1]);
        }
    }
}

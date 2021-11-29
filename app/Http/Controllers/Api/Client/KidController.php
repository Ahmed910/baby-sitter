<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\KidRequest;
use App\Http\Resources\Api\Client\KidResource;
use App\Models\Kid;
use Illuminate\Http\Request;

class KidController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kids = Kid::where('client_id',auth('api')->id())->get();
        return KidResource::collection($kids)->additional(['status'=>'success','message'=>'']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KidRequest $request)
    {
        Kid::create($request->validated()+['client_id'=>auth('api')->id()]);
        return response()->json(['data'=>null,'status'=>'success','message'=>trans('api.messages.kid_added_successfully')]);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(KidRequest $request, $id)
    {
        $kid = Kid::where('client_id',auth('api')->id())->findOrFail($id);
        $kid->update($request->validated());
        return response()->json(['data'=>null,'status'=>'success','message'=>trans('api.messages.kid_updated_successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kid = Kid::where('client_id',auth('api')->id())->findOrFail($id);
        if($kid){
            $kid->delete();
            return response()->json(['data'=>null,'status'=>'success','message'=>trans('api.messages.kid_deleted_successfully')]);
        }
    }
}

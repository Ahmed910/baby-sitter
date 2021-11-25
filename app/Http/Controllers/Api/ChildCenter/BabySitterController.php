<?php

namespace App\Http\Controllers\Api\ChildCenter;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ChildCenter\BabySitterRequest;
use App\Models\BabySitter;
use Illuminate\Http\Request;

class BabySitterController extends Controller
{
    public function store(BabySitterRequest $request)
    {
        BabySitter::create($request->validated()+['center_id'=>auth('api')->id()]);
        return response()->json(['data'=>null,'status'=>'success','message'=>trans('api.messages.baby_sitter_added_successfully')]);
    }

    public function update(BabySitterRequest $request,BabySitter $baby_sitter)
    {

        $baby_sitter->update($request->validated()+['center_id'=>auth('api')->id()]);
        return response()->json(['data'=>null,'status'=>'success','message'=>trans('api.messages.baby_sitter_data_updated_successfully')]);
    }

    public function destroy($id)
    {
        $sitter = BabySitter::where('center_id',auth('api')->id())->findOrFail($id);
        if($sitter){
            $sitter->delete();
            return response()->json(['data'=>null,'status'=>'success','message'=>trans('api.messages.sitter_deleted_successfully')]);
        }
    }
}

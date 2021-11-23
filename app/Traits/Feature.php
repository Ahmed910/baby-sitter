<?php

namespace App\Traits;

use App\Http\Requests\Api\Features\EditFeaturesRequest;

trait Feature{

    protected function editFeatures(EditFeaturesRequest $request)
    {

        auth('api')->user()->features()->sync($request->features);
        return response()->json(['data'=>null,'status'=>'success','message'=>trans('api.messages.features_updated_successfully')]);
    }
}

?>

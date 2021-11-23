<?php

namespace App\Http\Controllers\Api\ChildCenter;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Features\EditFeaturesRequest;
use App\Traits\Feature;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    use Feature;
    public function editFeaturesForCenter(EditFeaturesRequest $request)
    {
       return  $this->editFeatures($request);
    }
}

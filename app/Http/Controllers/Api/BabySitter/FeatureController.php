<?php

namespace App\Http\Controllers\Api\BabySitter;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Features\EditFeaturesRequest;
use App\Traits\Feature;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    use Feature;
    public function editFeaturesForSitter(EditFeaturesRequest $request)
    {
       return  $this->editFeatures($request);
    }
}

<?php

namespace App\Http\Controllers\Api\Help;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Help\FaqResource;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function getFaqs()
    {
        $faqs = Faq::active()->get();
        return FaqResource::collection($faqs)->additional(['status'=>'success','message'=>'']);
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferRequestController extends Controller
{
    public function index()
    {
        $offer_requests = Offer::paginate(100);
        return view('dashboard.offer_request.index',compact('offer_requests'));
    }
}

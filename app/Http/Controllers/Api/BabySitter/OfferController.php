<?php

namespace App\Http\Controllers\Api\BabySitter;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BabySitter\Offers\OfferRequest;
use App\Http\Requests\Api\BabySitter\Offers\PayOfferRequest;
use App\Models\Offer;
use App\Traits\Offers;
use Illuminate\Http\Request;


class OfferController extends Controller
{
    use Offers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->getOffers();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OfferRequest $request)
    {
        return $this->CreateOffer($request);
    }

    public function payOffer(PayOfferRequest $request, $id)
    {
        return $this->pay($request, $id);
    }

    public function inactiveOffer($id)
    {
        return $this->inactiveForOffer($id);
    }

    public function reactiveOffer(OfferRequest $request,$id)
    {
        return $this->reactiveForOffer($request,$id);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->getSingleOffer($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OfferRequest $request, $id)
    {
        return $this->UpdateOffer($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Api\BabySitter;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Galleries\AddToGalleryRequest;
use App\Http\Resources\Api\User\SitterInfoResource;
use App\Traits\Profile;
use Illuminate\Http\Request;

class GalleryController extends Controller
{

    use Profile;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth('api')->user();

        return (new SitterInfoResource($user))->additional(['status'=>'success','message'=>'']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddToGalleryRequest $request)
    {
        return $this->addToGallery($request);
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
    public function update(AddToGalleryRequest $request, $id)
    {
        return $this->updateGallery($request,$id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->deleteFromGallery($id);
    }
}

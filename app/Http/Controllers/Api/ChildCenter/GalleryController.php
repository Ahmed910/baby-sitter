<?php

namespace App\Http\Controllers\Api\ChildCenter;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Galleries\AddToGalleryRequest;
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

<?php

namespace App\Traits;

use App\Http\Requests\Api\Features\EditFeaturesRequest;
use App\Http\Requests\Api\Galleries\AddToGalleryRequest;
use App\Models\Gallery;

trait Profile
{

    protected function addToGallery(AddToGalleryRequest $request)
    {
        $user = auth('api')->user();
        if ($request->hasFile('image')) {

            $image = uploadImg($request->image, 'user');
            $user->galleries()->create(['image' => $image]);
            return response()->json(['data' => null, 'status' => 'success', 'message' => trans('api.messages.added_to_gallery')]);
        }

        return response()->json(['data' => null, 'status' => 'success', 'message' => trans('api.messages.there_is_an_error_try_again')], 401);
    }


    protected function updateGallery(AddToGalleryRequest $request, $gallery_id)
    {

        $user = auth('api')->user();
        $gallery = Gallery::where('user_id', $user->id)->findOrFail($gallery_id);

        if (isset($gallery) && $gallery) {

            if (file_exists(storage_path('app/public/images/user/' . $gallery->image))) {
                \File::delete(storage_path('app/public/images/user/' . $gallery->image));
            }

            // $gallery->delete();
            if ($request->hasFile('image')) {
                $image = uploadImg($request->image, 'user');
                $gallery->update(['image' => $image]);
            }

            return response()->json(['data' => null, 'status' => 'success', 'message' => trans('api.messages.update_gallery')]);
        }

        return response()->json(['data' => null, 'status' => 'success', 'message' => trans('api.messages.there_is_an_error_try_again')], 401);
    }

    protected function deleteFromGallery($gallery_id)
    {

        $gallery = Gallery::where('user_id', auth('api')->id())->findOrFail($gallery_id);
        if (isset($gallery) && $gallery) {

            if (file_exists(storage_path('app/public/images/user/' . $gallery->image))) {
                \File::delete(storage_path('app/public/images/user/' . $gallery->image));
            }
            $gallery->delete();
            return response()->json(['data' => null, 'status' => 'success', 'message' => trans('api.messages.deleted_from_gallery')]);
        }
        return response()->json(['data' => null, 'status' => 'success', 'message' => trans('api.messages.there_is_an_error_try_again')], 401);
    }

   
}

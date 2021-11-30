<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Client\FavoriteResource;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggleFavorites($user_id)
    {
        $favorite = Favorite::where(['client_id'=>auth('api')->id(),'user_id'=>$user_id])->first();

        if(!$favorite){

            Favorite::create(['client_id'=>auth('api')->id(),'user_id'=>$user_id]);
            return response()->json(['data'=>null,'status'=>'success','message'=>trans('api.messages.added_to_fav')]);
        }
        $favorite->delete();
        return response()->json(['data'=>null,'status'=>'success','message'=>trans('api.messages.deleted_from_fav')]);



    }


    public function getFavorites()
    {
        $favorites = Favorite::where('client_id',auth('api')->id())->get();
        return FavoriteResource::collection($favorites)->additional(['status'=>'success','message'=>'']);
    }

    public function deleteUserFromFavorites($fav_id)
    {
        $favorite = Favorite::findOrFail($fav_id);
        $favorite->delete();
        return response()->json(['data'=>null,'status'=>'success','message'=>trans('api.messages.deleted_from_fav')]);
    }
}

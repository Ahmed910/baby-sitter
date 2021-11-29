<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggleFavorites($user_id)
    {
        $favorite = Favorite::where(['client_id'=>auth('api')->id(),'user_id'=>$user_id])->first();
        
    }
}

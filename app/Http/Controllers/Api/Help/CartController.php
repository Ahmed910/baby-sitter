<?php

namespace App\Http\Controllers\Api\Help;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cart\AddToCartRequest;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(AddToCartRequest $request)
    {
       
        foreach($request->cart as $cart){

            Cart::create(['main_category_id'=>$cart['main_category_id'],'sub_category_id'=>$cart['sub_category_id']??null,'second_category_id'=>$cart['second_category_id']??null,'selender_id'=>$cart['selender_id']??null,'price'=>$cart['price']]);
        }
        return response()->json(['data'=>null,'status'=>'success','message'=>trans('api.messages.successfully_added_to_cart')]);
    }
}

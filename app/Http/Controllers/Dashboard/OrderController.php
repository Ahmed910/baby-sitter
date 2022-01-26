<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CenterOrder;
use App\Models\MainOrder;
use App\Models\SitterOrder;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = MainOrder::paginate(100);
        return view('dashboard.orders.index',compact('orders'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data=[];
        $order = MainOrder::findOrFail($id);
        $data['order'] = $order;
        if($order->to == 'sitter'){
            $data['order_details'] = $order->sitter_order;
            $data['name'] = optional($order->sitter)->name;

        }else{
            $data['order_details'] = $order->center_order;
            $data['name'] = optional($order->center)->name.' ('.optional($order->baby_sitter)->name.')';

        }

        // dd($order_details);
        return view('dashboard.orders.show',$data);

    }

    public function destroy($id)
    {
        $order = MainOrder::findOrFail($id);
        if ($order->delete()) {
          return response()->json(['value' => 1]);
        }
    }


}

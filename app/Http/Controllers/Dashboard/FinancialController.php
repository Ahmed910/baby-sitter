<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\MainOrder;
use Illuminate\Http\Request;

class FinancialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $orders = MainOrder::whereNotNull('finished_at')->get();

           // total profits for all orders
            $data['today_profits_bookings'] = $orders->filter(function ($item) {

                if ($item->finished_at->format('Y-m-d') == now()->format('Y-m-d')) {

                    return $item;
                }
            })->sum('app_profit');


            $data['current_week_profits_bookings'] = $orders->filter(function ($item) {
                if ($item->finished_at->format('Y-m-d') >= now()->subWeek()->format('Y-m-d') && $item->finished_at->format('Y-m-d') <= now()->format('Y-m-d')) {
                    return $item;
                }
            })->sum('app_profit');

            $data['current_month_profits_bookings'] = $orders->filter(function ($item) {
                if ($item->finished_at->format('Y-m-d') >= now()->subMonth()->format('Y-m-d') && $item->finished_at->format('Y-m-d') <= now()->format('Y-m-d')) {
                    return $item;
                }
            })->sum('app_profit');

            $data['current_year_profits_bookings'] = $orders->filter(function ($item) {
                if ($item->finished_at->format('Y-m-d') >= now()->subYear()->format('Y-m-d') && $item->finished_at->format('Y-m-d') <= now()->format('Y-m-d')) {
                    return $item;
                }
            })->sum('app_profit');

            // total profits for sitter orders

            $sitter_orders = MainOrder::whereNotNull('finished_at')->where('to','sitter')->get();
            $data['today_profits_for_sitter_orders'] = $sitter_orders->filter(function ($item) {

                if ($item->finished_at->format('Y-m-d') == now()->format('Y-m-d')) {

                    return $item;
                }
            })->sum('app_profit');


            $data['current_week_profits_for_sitter_orders'] = $sitter_orders->filter(function ($item) {
                if ($item->finished_at->format('Y-m-d') >= now()->subWeek()->format('Y-m-d') && $item->finished_at->format('Y-m-d') <= now()->format('Y-m-d')) {
                    return $item;
                }
            })->sum('app_profit');

            $data['current_month_profits_for_sitter_orders'] = $sitter_orders->filter(function ($item) {
                if ($item->finished_at->format('Y-m-d') >= now()->subMonth()->format('Y-m-d') && $item->finished_at->format('Y-m-d') <= now()->format('Y-m-d')) {
                    return $item;
                }
            })->sum('app_profit');

            $data['current_year_profits_for_sitter_orders'] = $sitter_orders->filter(function ($item) {
                if ($item->finished_at->format('Y-m-d') >= now()->subYear()->format('Y-m-d') && $item->finished_at->format('Y-m-d') <= now()->format('Y-m-d')) {
                    return $item;
                }
            })->sum('app_profit');

            // dd($data['current_year_profits_bookings']);

            $center_orders = MainOrder::whereNotNull('finished_at')->where('to','center')->get();
            $data['today_profits_for_center_orders'] = $center_orders->filter(function ($item) {

                if ($item->finished_at->format('Y-m-d') == now()->format('Y-m-d')) {

                    return $item;
                }
            })->sum('app_profit');


            $data['current_week_profits_for_center_orders'] = $center_orders->filter(function ($item) {
                if ($item->finished_at->format('Y-m-d') >= now()->subWeek()->format('Y-m-d') && $item->finished_at->format('Y-m-d') <= now()->format('Y-m-d')) {
                    return $item;
                }
            })->sum('app_profit');

            $data['current_month_profits_for_center_orders'] = $center_orders->filter(function ($item) {
                if ($item->finished_at->format('Y-m-d') >= now()->subMonth()->format('Y-m-d') && $item->finished_at->format('Y-m-d') <= now()->format('Y-m-d')) {
                    return $item;
                }
            })->sum('app_profit');

            $data['current_year_profits_for_center_orders'] = $center_orders->filter(function ($item) {
                if ($item->finished_at->format('Y-m-d') >= now()->subYear()->format('Y-m-d') && $item->finished_at->format('Y-m-d') <= now()->format('Y-m-d')) {
                    return $item;
                }
            })->sum('app_profit');


            return view('dashboard.financial_statistics.index', $data);
    }


}

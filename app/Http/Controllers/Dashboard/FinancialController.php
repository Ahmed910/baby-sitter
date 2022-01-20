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
        $bookings = MainOrder::where('status','finished')->get();
        // dd(now()->format('Y-m-d'));
            $data['today_profits_bookings'] = $bookings->filter(function ($item) {

                if ($item->finished_at->format('Y-m-d') == now()->format('Y-m-d')) {

                    return $item;
                }
            })->sum('app_profit_amount');
            // dd($data['today_profits_bookings']);
            $data['current_week_profits_bookings'] = $bookings->filter(function ($item) {
                if ($item->finished_at->format('Y-m-d') >= now()->subWeek()->format('Y-m-d') && $item->finished_at->format('Y-m-d') <= now()->format('Y-m-d')) {
                    return $item;
                }
            })->sum('app_profit_amount');
            $data['current_month_profits_bookings'] = $bookings->filter(function ($item) {
                if ($item->finished_at->format('Y-m-d') >= now()->subMonth()->format('Y-m-d') && $item->finished_at->format('Y-m-d') <= now()->format('Y-m-d')) {
                    return $item;
                }
            })->sum('app_profit_amount');
            $data['current_year_profits_bookings'] = $bookings->filter(function ($item) {
                if ($item->finished_at->format('Y-m-d') >= now()->subYear()->format('Y-m-d') && $item->finished_at->format('Y-m-d') <= now()->format('Y-m-d')) {
                    return $item;
                }
            })->sum('app_profit_amount');
            return view('dashboard.financial_statistics.index', $data);
    }


}

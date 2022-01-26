<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\{City , Country , User , Driver, MainOrder, SitterOrder};
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!request()->ajax()) {
            //statistics
            $data['countries_count'] = Country::count();
            $data['cities_count'] = City::count();

            $data['managers_count'] = User::where('user_type' , 'admin')->latest()->count();

            $clients = \DB::table('users')->where('user_type' , 'client')->get();
            $data['clients_count'] = $clients->count();
            $data['clients_is_ban_count'] = $clients->where('is_ban',1)->count();
            $data['clients_is_deactive_count'] = $clients->where('is_active',0)->count();

            $baby_sitters = \DB::table('users')->where('user_type' , 'babysitter')->get();
            $data['baby_sitters_count'] = $baby_sitters->count();
            $data['baby_sitters_is_deactive_count'] = $baby_sitters->where('is_active',0)->count();
            $data['baby_sitters_is_ban_count'] = $baby_sitters->where('is_ban',1)->count();

            $child_center = \DB::table('users')->where('user_type' , 'childcenter')->get();
            $data['child_center_count'] = $child_center->count();
            $data['child_center_is_deactive_count'] = $child_center->where('is_active',0)->count();
            $data['child_center_is_ban_count'] = $child_center->where('is_ban',1)->count();


            // $booking_query = MainOrder::get();
            // $data['bookings'] = $booking_query;
            // $data['bookings_count'] = $booking_query->count();
            // $sitter_orders = SitterOrder::get();
            // $data['rejected_sitters_bookings_count'] = $sitter_orders->where('status','rejected')->count();
            // $data['accepted_sitters_bookings_count'] = $sitter_orders->where('status','accepted')->count();
            // $data['canceled_sitters_bookings_count'] = $sitter_orders->where('status','canceled')->count();

            $from_date = now()->subMonths(11)->format('Y-m-d');
            $to_date = now()->format('Y-m-d');
            if ($request->from_date && $request->to_date) {
                $from_date = \Carbon\Carbon::parse($request->from_date)->format('Y-m-d');
                $to_date = \Carbon\Carbon::parse($request->to_date)->format('Y-m-d');
            }elseif ($request->from_date) {
                $from_date = \Carbon\Carbon::parse($request->from_date)->format('Y-m-d');
            }elseif ($request->to_date) {
                $to_date = \Carbon\Carbon::parse($request->to_date)->format('Y-m-d');
            }
            // $data['from_date'] = clone($from_date);
            // $data['to_date'] = clone($to_date);
            // <==============================Charts============================>
            $client_analytics = $clients->when($request->from_date || $request->to_date,function($collection)use($from_date,$to_date){
                if ($from_date && $to_date) {
                    return $collection->filter(function($item)use($from_date,$to_date){
                            if (\Carbon\Carbon::parse($item->created_at)->format("Y-m-d") <= $to_date && \Carbon\Carbon::parse($item->created_at)->format("Y-m-d") >= $from_date) {
                                return $item;
                            }
                        });
                }elseif ($from_date) {
                    return $collection->filter(function($item)use($from_date){
                            if (\Carbon\Carbon::parse($item->created_at)->format("Y-m-d") >= $from_date) {
                                return $item;
                            }
                        });
                }elseif ($to_date) {
                    return $collection->filter(function($item)use($to_date){
                        if (\Carbon\Carbon::parse($item->created_at)->format("Y-m-d") <= $to_date) {
                            return $item;
                        }
                    });
                }
            })->groupBy(function ($date) {
                return \Carbon\Carbon::parse($date->created_at)->format('Y-m');
            });
            $babysitter_analytics = $baby_sitters->when($request->from_date || $request->to_date,function($collection)use($from_date,$to_date){
                if ($from_date && $to_date) {
                    return $collection->filter(function($item)use($from_date,$to_date){
                            if (\Carbon\Carbon::parse($item->created_at)->format("Y-m-d") <= $to_date && \Carbon\Carbon::parse($item->created_at)->format("Y-m-d") >= $from_date) {
                                return $item;
                            }
                        });
                }elseif ($from_date) {
                    return $collection->filter(function($item)use($from_date){
                            if (\Carbon\Carbon::parse($item->created_at)->format("Y-m-d") >= $from_date) {
                                return $item;
                            }
                        });
                }elseif ($to_date) {
                    return $collection->filter(function($item)use($to_date){
                        if (\Carbon\Carbon::parse($item->created_at)->format("Y-m-d") <= $to_date) {
                            return $item;
                        }
                    });
                }
            })->groupBy(function ($date) {
                return \Carbon\Carbon::parse($date->created_at)->format('Y-m');
            });

            $childcenter_analytics = $child_center->when($request->from_date || $request->to_date,function($collection)use($from_date,$to_date){
                if ($from_date && $to_date) {
                    return $collection->filter(function($item)use($from_date,$to_date){
                            if (\Carbon\Carbon::parse($item->created_at)->format("Y-m-d") <= $to_date && \Carbon\Carbon::parse($item->created_at)->format("Y-m-d") >= $from_date) {
                                return $item;
                            }
                        });
                }elseif ($from_date) {
                    return $collection->filter(function($item)use($from_date){
                            if (\Carbon\Carbon::parse($item->created_at)->format("Y-m-d") >= $from_date) {
                                return $item;
                            }
                        });
                }elseif ($to_date) {
                    return $collection->filter(function($item)use($to_date){
                        if (\Carbon\Carbon::parse($item->created_at)->format("Y-m-d") <= $to_date) {
                            return $item;
                        }
                    });
                }
            })->groupBy(function ($date) {
                return \Carbon\Carbon::parse($date->created_at)->format('Y-m');
            });

            $diffInMonths = \Carbon\Carbon::parse($from_date)->diffInMonths(\Carbon\Carbon::parse($to_date));
            $months_arr = [];
            for ($i = 0; $i <= $diffInMonths; $i++) {
                $months_arr[] = \Carbon\Carbon::parse($from_date)->addMonths($i)->format("Y-m");
                if ($i == 0) {
                    if (isset($client_analytics[\Carbon\Carbon::parse($to_date)->format('Y-m')])) {
                        $data['client_analytics'][\Carbon\Carbon::parse($to_date)->format('Y-m')] = $client_analytics[\Carbon\Carbon::parse($to_date)->format('Y-m')]->count();
                    } else {
                        $data['client_analytics'][\Carbon\Carbon::parse($to_date)->format('Y-m')] = 0;
                    }
                    if (isset($babysitter_analytics[\Carbon\Carbon::parse($to_date)->format('Y-m')])) {
                        $data['babysitter_analytics'][\Carbon\Carbon::parse($to_date)->format('Y-m')] = $babysitter_analytics[\Carbon\Carbon::parse($to_date)->format('Y-m')]->count();
                    } else {
                        $data['babysitter_analytics'][\Carbon\Carbon::parse($to_date)->format('Y-m')] = 0;
                    }
                    if (isset($childcenter_analytics[\Carbon\Carbon::parse($to_date)->format('Y-m')])) {
                        $data['childcenter_analytics'][\Carbon\Carbon::parse($to_date)->format('Y-m')] = $childcenter_analytics[\Carbon\Carbon::parse($to_date)->format('Y-m')]->count();
                    } else {
                        $data['childcenter_analytics'][\Carbon\Carbon::parse($to_date)->format('Y-m')] = 0;
                    }
                } else {
                    if (isset($client_analytics[\Carbon\Carbon::parse($to_date)->subMonths($i)->format('Y-m')])) {
                        $data['client_analytics'][\Carbon\Carbon::parse($to_date)->subMonths($i)->format('Y-m')] = $client_analytics[\Carbon\Carbon::parse($to_date)->subMonths($i)->format('Y-m')]->count();
                    } else {
                        $data['client_analytics'][\Carbon\Carbon::parse($to_date)->subMonths($i)->format('Y-m')] = 0;
                    }
                    if (isset($babysitter_analytics[\Carbon\Carbon::parse($to_date)->subMonths($i)->format('Y-m')])) {
                        $data['babysitter_analytics'][\Carbon\Carbon::parse($to_date)->subMonths($i)->format('Y-m')] = $babysitter_analytics[\Carbon\Carbon::parse($to_date)->subMonths($i)->format('Y-m')]->count();
                    } else {
                        $data['babysitter_analytics'][\Carbon\Carbon::parse($to_date)->subMonths($i)->format('Y-m')] = 0;
                    }

                    if (isset($childcenter_analytics[\Carbon\Carbon::parse($to_date)->subMonths($i)->format('Y-m')])) {
                        $data['childcenter_analytics'][\Carbon\Carbon::parse($to_date)->subMonths($i)->format('Y-m')] = $childcenter_analytics[\Carbon\Carbon::parse($to_date)->subMonths($i)->format('Y-m')]->count();
                    } else {
                        $data['childcenter_analytics'][\Carbon\Carbon::parse($to_date)->subMonths($i)->format('Y-m')] = 0;
                    }

                }
            }

            $data['months_arr'] = $months_arr;



          return view('dashboard.home.index' , $data);
        }
    }

    // Search Method

    public function getSearch(Request $request)
    {
        $query = request()->search;
        $request->validate([
            'search' => 'required'
        ]);
        $user_query = User::latest();
        $clients = $user_query->where('user_type','client')->where(function($q)use($query){
            $q->where('fullname',"LIKE","%{$query}%")->orWhere('email',"LIKE","%{$query}%")->orWhere('phone',"LIKE","%{$query}%");
        });

        $drivers = $user_query->where('user_type','driver')->where(function($q)use($query){
            $q->where('fullname',"LIKE","%{$query}%")->orWhere('email',"LIKE","%{$query}%")->orWhere('phone',"LIKE","%{$query}%");
        });

        $admins = $user_query->where('user_type','admin')->where(function($q)use($query){
            $q->where('fullname',"LIKE","%{$query}%")->orWhere('email',"LIKE","%{$query}%")->orWhere('phone',"LIKE","%{$query}%");
        })->where('id',"<>",auth()->id());

        $brands = Brand::whereTranslationLike('name',"%{$query}%",'ar')->orWhereTranslationLike('name',"%{$query}%",'en')->orWhereTranslationLike('desc',"%{$query}%",'ar')->orWhereTranslationLike('desc',"%{$query}%",'en');

        $search_type = 'client';
        if (array_key_exists('admin',$request->query()) || ($admins->count() && !$clients->count())) {
            $search_type = 'admin';
        }elseif (array_key_exists('driver',$request->query()) || (!$admins->count() && !$clients->count() && $drivers->count())) {
            $search_type = 'driver';
        }

        $data = [
                'clients_count' => $clients->count(),
                'admins_count' => $admins->count(),
                'drivers_count' => $drivers->count(),

                'clients' => $clients->paginate(50,['*'],'client'),
                'admins' => $admins->paginate(50,['*'],'admin'),
                'drivers' => $drivers->paginate(50,['*'],'driver'),

                'keyword' => $query,
                'search_type' => $search_type,
                'total_count' => $clients->count() + $admins->count() + $drivers->count()
            ];
        return view('dashboard.search.search',$data);
    }

}

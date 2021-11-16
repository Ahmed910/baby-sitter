<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\User\DriverRequest;
use App\Models\{User ,City ,Country , Car};
use Carbon\Carbon;
use App\Http\Resources\Dashboard\Driver\DriverResource;

class DriverController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        Carbon::setWeekStartsAt(Carbon::SATURDAY);
        Carbon::setWeekEndsAt(Carbon::FRIDAY);

        $query = User::where('user_type' , 'driver')->when($request->status,function ($q) use($request) {
            switch ($request->status) {
                case 'deactive':
                    $q->where('is_active',0);
                    break;
                case 'ban':
                    $q->where('is_ban',1);
                    break;
                case 'with_special_needs':
                    $q->where('is_with_special_needs',1);
                    break;
                case 'accepted':
                    $q->whereHas('driver',function ($q) {
                        $q->where('is_admin_accept',1);
                    });
                    break;
                case 'paid':
                    $q->whereHas('subscribedPackage',function ($q) {
                        $q->whereDate('package_drivers.end_at',">",date("Y-m-d"))->where('is_paid',1);
                    });
                    break;
                case 'wait_accept_drivers':
                    $q->whereHas('driver',function ($q) {
                        $q->where('is_admin_accept',0)->where(function ($q) {
                            $q->whereNull('accepted_status')->orWhere('accepted_status','waiting');
                        });
                    });
                    break;
                case 'refused_drivers':
                    $q->whereHas('driver',function ($q) {
                        $q->where('is_admin_accept',0)/*->where('accepted_status','refused')*/;
                    });
                    break;
                case 'available':
                    $q->where(function ($q) {
                        $q->whereHas('driver',function ($q) {
                                $q->where('drivers.is_on_default_package',1)->where(['is_available' => 1 , 'is_driver_available' => 1 , 'is_admin_accept' => 1]);
                            })->where('wallet',">",-((float)setting('min_wallet_to_recieve_order')));
                    });
                    break;
                case 'disable_to_recieve_orders':
                    $q->where(function ($q) {
                        $q->whereHas('driver',function ($q) {
                            $q->where(['is_admin_accept' => 0])->whereHas('subscribedPackage',function ($q) {
                                $q->whereDate('package_drivers.end_at',"<",date("Y-m-d"))->where('package_drivers.is_paid',false);
                            });
                        });
                    });
                    break;
                case 'enable_to_recieve_orders':
                    $q->where(function ($q) {
                        $q->whereHas('driver',function ($q) {
                            $q->where(['is_available' => 1])->whereHas('subscribedPackage',function ($q) {
                                $q->whereDate('package_drivers.end_at',">=",date("Y-m-d"))->where('package_drivers.is_paid',true);
                            });
                        });
                    });
                    break;
                case 'not_developer_available':
                    $q->whereHas('driver',function ($q) {
                        $q->where(['is_available' => 1]);
                     });
                    break;
                case 'not_available':
                    // $q->whereHas('driver',function ($q) {
                    //     $q->where(['is_on_default_package' => true , 'is_admin_accept' => 1])
                    //         ->whereHas('user',function ($q) {
                    //              $q->where('wallet',"<=",-(setting('min_wallet_to_recieve_order') ?? 10));
                    //        });
                    //    });
                    $q->whereHas('driver',function ($q) {
                        $q->whereHas('subscribedPackage',function ($q) {
                            $q->whereDate('package_drivers.end_at',"<",date("Y-m-d"))->orWhere('package_drivers.is_paid',false);
                        })->orWhereNull('subscribed_package_id');
                    });
                    break;
                case 'drivers_subscribed_this_week':
                    $q->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'both_type':
                    $q->whereHas('driver',function ($q) {
                        $q->where(['driver_type' => 'both']);
                     });
                    break;
                case 'delivery':
                    $q->whereHas('driver',function ($q) {
                        $q->where(['driver_type' => 'delivery']);
                     });
                    break;
                case 'ride':
                    $q->whereHas('driver',function ($q) {
                        $q->where(['driver_type' => 'ride']);
                     });
                    break;
                case 'monthly_drivers':
                    $q->whereHas('driver',function ($q) {
                        $q->where(['is_on_default_package' => 0]);
                     });
                    break;
                case 'on_order_drivers':
                    $q->whereHas('driver',function ($q) {
                        $q->where(['is_on_default_package' => 1]);
                     });
                    break;
                case 'has_balance_in_wallet':
                    $q->where('wallet',">",0);
                    break;
            }
        });
        $driver_count = $query->count();
        $driver_side_cols = [
            'id','image','fullname','email','phone','identity_number','wallet','created_at'
        ];
        if (request()->ajax()) {
            $search = $request->search['value'];
            $drivers = $query->when($search,function($q)use($search){
                $q->where(function($q)use($search){
                    $q->where('fullname',"LIKE","%{$search}%")->orWhere('email',"LIKE","%{$search}%")->orWhere('phone',"LIKE","%{$search}%");
                });
            });

            $driver_count = $drivers->count();
            $drivers = $drivers->when(isset($driver_side_cols[$request['order'][0]['column']]),function ($q) use($request , $driver_side_cols) {
                $q->orderBy($driver_side_cols[$request['order'][0]['column']],$request['order'][0]['dir']);
            })->when(!isset($driver_side_cols[$request['order'][0]['column']]),function ($q) {
                $q->latest();
            })->skip($request['start'])->take($request['length'] == '-1' ? $driver_count : $request['length'])->get();

            return (new DriverResource($drivers))->additional(['driver_count' => $driver_count]);
        }

        if (!request()->ajax()) {
            return view('dashboard.driver.index',compact('driver_count'));
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      if (!request()->ajax()) {
          $data['cities'] = City::get()->pluck('name','id');
          $data['countries'] = Country::get()->pluck('nationality','id');
          $data['cars'] = Car::latest()->get();
          return view('dashboard.driver.create',$data);
      }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DriverRequest $request)
    {
        if (!request()->ajax()) {
            \DB::beginTransaction();
            try {
                $driver = User::create(array_except($request->validated(),['country_id','city_id','car_id','is_admin_accept','is_available','identity_number_image'])+['user_type' => 'driver' , 'verified_code' => ($request->is_active ? null : 111111) , 'referral_code' => generate_unique_code(8,'\\App\\Models\\User','referral_code','alpha_numbers','lower')]);
                $driver->profile()->create(array_only($request->validated(),['country_id','city_id'])+['added_by_id' => auth()->id()]);
                $driver->driver()->create(array_only($request->validated(),['is_admin_accept','is_available'])+['is_on_default_package' => true]);
                $car = Car::find($request->car_id);
                if ($car) {
                    $car->update(['driver_id' => $driver->id]);
                    $driver->driver()->update(['is_admin_accept' => 0]);
                }
                \DB::commit();
                if ($driver->is_active) {
                    send_sms($driver->phone,trans('dashboard.messages.now_u_can_login_to_app'));
                }
               return redirect(route('dashboard.driver.index'))->withTrue(trans('dashboard.messages.success_add'));
           }catch (\Exception $e) {
               \DB::rollback();
               return back()->withInput()->withFalse(trans('dashboard.messages.something_went_wrong_please_try_again'));
           }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $driver
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request , $id)
    {
        if (!$request->ajax()) {
            $driver = User::where('user_type','driver')->findOrFail($id);
            $data['other_drivers'] = User::where('user_type','driver')->where('id',"<>",$driver->id)->inRandomOrder()->take(5)->get();
            $data['total_drivers'] = User::where('user_type','driver')->count();
            $data['offers'] = $driver->driverOffers()->latest()->paginate(12);
            $data['all_packages'] = Package::active()->latest()->get()->pluck('name','id');
            $data['packages'] = $driver->driverPackages()->latest()->paginate(12);
            $data['orders'] = $driver->driverOrders()->latest()->paginate(20);
            $data['points'] = $driver->userPoints()->latest()->paginate(20);
            $data['wallet_transactions'] = WalletTransaction::where('user_id',$driver->id)->orWhere('added_by_id',$driver->id)->latest()->paginate(30);
            $data['wallet_transfers'] = MoneyTransfer::where('transfer_to_id',$driver->id)->orWhere('transfer_from_id',$driver->id)->paginate(50);
            $data['driver'] = $driver;

            foreach (auth()->user()->unreadNotifications as $notification) {
                if (isset($notification->data['driver_id']) && $notification->data['driver_id'] == $data['driver']->id && ! $notification->read_at) {
                    $notification->markAsRead();
                }
            }
            return view('dashboard.driver.show',$data);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $driver
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!request()->ajax()) {
            $data['driver'] = User::where('user_type','driver')->findOrFail($id);
            $data['cities'] = City::get()->pluck('name','id');
            $data['countries'] = Country::get()->pluck('nationality','id');
            $data['cars'] = Car::latest()->get();
           return view('dashboard.driver.edit',$data);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(DriverRequest $request, $id)
    {
        if (!request()->ajax()) {
            $driver = User::where('user_type','driver')->findOrFail($id);
            $is_active = $driver->is_active;
            \DB::beginTransaction();
            try {
                $driver->update(array_except($request->validated(),['country_id','city_id','car_id','is_admin_accept','is_available','identity_number_image'])+['verified_code' => $request->is_active ? null : 1111]);

                $driver->profile()->updateOrCreate(['user_id' => $driver->id],array_only($request->validated(),['country_id','city_id']));

                $driver->driver()->updateOrCreate(['user_id' => $driver->id],array_only($request->validated(),['is_admin_accept','is_available']));

                $car = Car::find($request->car_id);
                if ($car) {
                    $car->update(['driver_id' => $driver->id]);
                }else{
                    $driver->car()->update(['driver_id' => null]);
                }
                \DB::commit();
                if (!$is_active && $driver->is_active) {
                    send_sms($driver->phone,trans('dashboard.messages.now_u_can_login_to_app'));
                }
                return redirect(route('dashboard.driver.index'))->withTrue(trans('dashboard.messages.success_update'));
            }catch (\Exception $e) {
                \DB::rollback();
                return back()->withInput()->withFalse(trans('dashboard.messages.something_went_wrong_please_try_again'));
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $driver = User::where('user_type','driver')->findOrFail($id);
        if ($driver->delete()) {
          return response()->json(['value' => 1]);
        }
    }
}

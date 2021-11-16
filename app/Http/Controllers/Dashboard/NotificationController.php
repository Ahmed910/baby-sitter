<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\User\{NotificationRequest};
use App\Notifications\General\{GeneralNotification,FCMNotification};
use Illuminate\Notifications\DatabaseNotification;
use App\Jobs\SendFCMNotification;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! request()->ajax()) {
            $superAdmins = User::whereIn('user_type',['admin','superadmin'])->pluck('id');
            $notifications = DatabaseNotification::whereHasMorph('notifiable',[User::class],function($q) use($superAdmins){
                $q->whereIn('notifiable_id',$superAdmins);
            })->latest()->paginate(200);
            return view('dashboard.notification.index',compact('notifications'));
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Entity  $entity
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! request()->ajax()) {
            $superAdmins = User::whereIn('user_type',['admin','superadmin'])->pluck('id');
            $notification = DatabaseNotification::whereHasMorph('notifiable',[User::class],function($q) use($superAdmins){
                $q->whereIn('notifiable_id',$superAdmins);
            })->findOrFail($id);
            if (!$notification->read_at) {
               $notification->update(['read_at' => now()]);
            }
            return view('dashboard.notification.show',compact('notification'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function store(NotificationRequest $request)
     {
         if ($request->user_id == 'all') {
             $users = User::where('user_type',$request->user_type)->when($request->status,function ($q) use($request) {
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
                         $q->whereHas('subscribedPackage',function ($q) {
                                 $q->whereDate('package_drivers.end_at',">",date("Y-m-d"))->where('is_paid',1);
                             })->whereHas('driver',function ($q) {
                                 $q->where('is_admin_accept',1)->where('is_available',1);
                             });
                         break;
                     case 'not_available':
                        //  $q->whereHas('driver',function ($q) {
                        //      $q->where(['is_on_default_package' => true , 'is_admin_accept' => 1])
                        //      ->whereHas('user',function ($q) {
                        //           $q->where('wallet',"<=",-(setting('min_wallet_to_recieve_order') ?? 10));
                        //     });
                        // });
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
                     case 'has_balance_in_wallet':
                         $q->where('wallet',">",0);
                         break;
                 }
             })->when($request->user_list,function ($q) use($request) {
                 $q->whereIn('users.id',$request->user_list);
             })->get();
             $numbers = implode(',',array_filter($users->pluck('phone')->toArray()));
             $user_list = $users->pluck('id')->toArray();
         }else{
             $users = User::where('user_type',$request->user_type)->findOrFail($request->user_id);
             $numbers = $users->phone;
             $user_list = [$users->id];
         }
         // \Notification::send($users,new GeneralNotification($request->validated()+['notify_type' => 'management']));
         $pushFcmNotes    = [
           'notify_type'         => 'management',
           'title'        => $request->title??trans('api.management.management'),
           'body'         => $request->body,
         ];
         \Notification::send($users,new FCMNotification($pushFcmNotes,['database']));
         SendFCMNotification::dispatch($pushFcmNotes , $user_list)->onQueue('high');
         // if ($request->send_type == 'fcm') {
             // pushFcmNotes($pushFcmNotes, $user_list);
         // }else{
         //     send_sms($numbers,$request->body);
         // }
         if (!request()->ajax()) {
             return back()->withTrue(trans('dashboard.messages.success_send'));
         }else{
            return response()->json(['value' => 1 , 'body' => trans('dashboard.messages.success_send')]);
         }
     }


}

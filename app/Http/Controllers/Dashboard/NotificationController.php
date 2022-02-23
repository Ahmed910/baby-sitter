<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\User\{NotificationRequest};
use App\Http\Resources\Api\Notification\SenderResource;
use App\Notifications\General\{GeneralNotification,FCMNotification};
use Illuminate\Notifications\DatabaseNotification;
use App\Jobs\SendFCMNotification;
use App\Models\Device;
use App\Notifications\FCMNotifiations\FcmApiNotifications;

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
        $user = User::where('user_type', $request->user_type)->whereHas('devices', function ($q) use ($request) {
            $q->where('user_id', $request->user_id);
        })->find($request->user_id);
         // \Notification::send($users,new GeneralNotification($request->validated()+['notify_type' => 'management']));
         $pushFcmNotes    = [
           'notify_type'         => 'management',
           'title'        => $request->title??trans('api.management.management'),
           'body'         => $request->body,
           'sender_data'  => new SenderResource(auth()->user())
         ];
         if(isset($user)){
            $devices = Device::where('user_id', $user->id)->get();

             pushFcmNotes($pushFcmNotes, $devices);

             $user->notify(new FcmApiNotifications($pushFcmNotes));
         }


         //  \Notification::send($users,new FCMNotification($pushFcmNotes,['database']));

         //  SendFCMNotification::dispatch($pushFcmNotes , $user_list)->onQueue('high');

         if ($request->ajax() ) {


             // return back()->withTrue(trans('dashboard.messages.success_send'));
             return response()->json(['value' => 1, 'body' => trans('dashboard.messages.success_send')]);
         } else {
             return response()->json(['value' => 1, 'body' => trans('dashboard.messages.fail_send')]);
         }
        //  \Notification::send($users,new FCMNotification($pushFcmNotes,['database']));
        //  SendFCMNotification::dispatch($pushFcmNotes , $user_list)->onQueue('high');
         // if ($request->send_type == 'fcm') {
             // pushFcmNotes($pushFcmNotes, $user_list);
         // }else{
         //     send_sms($numbers,$request->body);
         // }
        //  if (!request()->ajax()) {
        //      return back()->withTrue(trans('dashboard.messages.success_send'));
        //  }else{
        //     return response()->json(['value' => 1 , 'body' => trans('dashboard.messages.success_send')]);
        //  }
     }


}

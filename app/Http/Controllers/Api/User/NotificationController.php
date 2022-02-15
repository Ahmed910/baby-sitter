<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Notification\{NotificationResource , NotificationCollection};
use Illuminate\Notifications\Notification;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = auth('api')->user()->notifications()->get();
        $unreadnotifications = auth('api')->user()->unreadNotifications;
        foreach ($unreadnotifications as $notification) {
             $notification->markAsRead();
        }
        return (new NotificationCollection($notifications))->additional(['status' => 'success','message'=>'']);
    }

    public function clearAllNotifications()
    {
        $notifications = auth('api')->user()->notifications()->get();
        $notification_ids = $notifications->pluck('id');
        Notification::whereIn('id',$notification_ids)->delete();
        return (new NotificationCollection($notifications))->additional(['status' => 'success','message'=>'']);
    }


    public function show($id)
    {
        $notification = auth('api')->user()->notifications()->findOrFail($id);
        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }
        return (new NotificationResource($notification))->additional(['status' => 'success','message'=>'']);
    }


    public function destroy($id)
    {
        $notification = auth('api')->user()->notifications()->findOrFail($id);
        $notification->delete();
        return (new NotificationResource($notification))->additional(['status' => 'success','message'=>'تم الحذف بنجاح']);
    }

}

<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Notification\{NotificationResource , NotificationCollection};

use Illuminate\Support\Facades\Notification;

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
        foreach ($notifications as $notification) {
            $notification->delete();
       }
        return (new NotificationCollection($notifications))->additional(['status' => 'success','message'=>trans('api.messages.notifictions_has_been_deleted')]);
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

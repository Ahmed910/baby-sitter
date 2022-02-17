<?php

namespace App\Traits;

use App\Http\Resources\Api\Notification\SenderResource;
use App\Models\User;
use App\Notifications\Orders\RecieveChildernNotification;
use Illuminate\Support\Facades\{DB, Notification};

trait WithTheChild
{
    public function updateOrderStatusToWithTheChild($sitter_order, $order, $updated_status)
    {

        $sitter_order->update(['status' => $updated_status, 'otp_code' => NULL]);
        // DB::commit();
        $fcm_notes = [
            'title' => ['dashboard.notification.sitter_has_been_recieved_childern_title'],
            'body' => ['dashboard.notification.sitter_has_been_recieved_childern_body', ['body' => auth('api')->user()->name ?? auth('api')->user()->phone]],
            'sender_data' => new SenderResource(auth('api')->user())
        ];
        $order->client->notify(new RecieveChildernNotification($order, ['database']));

        $admins = User::whereIn('user_type', ['superadmin', 'admin'])->get();
        Notification::send($admins, new RecieveChildernNotification($order, ['database', 'broadcast']));
        pushFcmNotes($fcm_notes, optional($order->client)->devices);
    }
}

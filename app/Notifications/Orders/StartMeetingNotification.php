<?php

namespace App\Notifications\Orders;

use App\Http\Resources\Api\Notification\SenderResource;
use App\Models\MainOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StartMeetingNotification extends Notification
{
    use Queueable;
    public $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(MainOrder $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toDatabase($notifiable)
    {
        return [
            'title'=>['dashboard.notification.meeting_has_been_started_title'],
            'body'=> ['dashboard.notification.meeting_has_been_started_body'],
            'sender_data' => new SenderResource(auth('api')->user()),
            'notify_type'=>'start_meeting',
            'route' => route('dashboard.orders.show',$this->order->id),
            'order_id' => optional($this->order)->id,
        ];
    }

}

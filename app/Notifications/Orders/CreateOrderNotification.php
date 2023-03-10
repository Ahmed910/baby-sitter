<?php

namespace App\Notifications\Orders;

use App\Http\Resources\Api\Notification\SenderResource;
use App\Models\MainOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CreateOrderNotification extends Notification implements ShouldBroadcastNow
{
    use Queueable;
    public $booking;
    public $via;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(MainOrder $mainOrder,$via)
    {

        $this->booking = $mainOrder;
        $this->via = $via;
        // dd($this->via);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $this->via;
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
            'title'=>['dashboard.notification.order_has_been_created_title'],
            'body'=> ['dashboard.notification.order_has_been_created_body'],
            'sender_data' => new SenderResource(auth('api')->user()),
            'notify_type'=>'new_order',
            'route' => route('dashboard.orders.show',$this->booking->id),
            'order_id' => optional($this->booking)->id,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toBroadcast($notifiable)
    {

        return new BroadcastMessage([
            'title'=> trans('dashboard.notification.order_has_been_created_title',[],$notifiable->current_lang),
            'body'=> trans('dashboard.notification.order_has_been_created_body',[],$notifiable->current_lang) . auth('api')->user()->name??auth('api')->user()->phone,
            'notify_type'=>'create_order',
            'route' => route('dashboard.orders.show',$this->booking->id),
            'order_id' => optional($this->booking)->id,
        ]);
    }
}

<?php

namespace App\Notifications\Order;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class OrderNotification extends Notification implements ShouldBroadcast
{
    use Queueable;
    public $data;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {

        $this->data = $data;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
     public function via($notifiable)
     {
         return ['database','broadcast'];
     }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'sender_id' => auth()->id()??auth('api')->id(),
            'sender' => auth()->user()??auth('api')->user(),
            'avatar' => auth()->user()->avatar??auth('api')->user()->avatar,
            'title' => trans('dashboard.notification.new_order_title'),
            'body' => trans('dashboard.notification.new_order_body',['body' => $this->data->client->fullname ?? $this->data->client->phone]),
            'route' => route('dashboard.order.show',$this->data->id),
            'created_at' => now()->isoFormat("D MMMM , Y ( h:mm a )"),
            'total_price' => $this->data->total_price,
            'order_id' => optional($this->data)->id,
            'order_status' => optional($this->data)->order_status,
            'order_ad' => optional($this->data)->ad_data,
            'notify_type' => 'order',
            'trans_order_status' => trans('dashboard.order.statuses.pending'),
            'order_type_trans' => trans('dashboard.order.order_types.'.$this->data->order_type),

        ]);
    }
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'sender_id' => auth()->id()??auth('api')->id(),
            'sender' => auth()->user()->toJson()??auth('api')->user()->toJson(),
            'avatar' => auth()->user()->avatar??auth('api')->user()->avatar,
            'title' => 'dashboard.notification.new_order_title',
            'body' => ['dashboard.notification.new_order_body',['body' => $this->data->client->fullname ?? $this->data->client->phone]],
            'created_at' => now()->isoFormat("D MMMM , Y ( h:mm a )"),
            'total_price' => $this->data->total_price,
            'order_id' => optional($this->data)->id,
            'order_status' => optional($this->data)->order_status,
            'notify_type' => 'order',
        ];
    }
}

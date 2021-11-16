<?php

namespace App\Notifications\Order;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class ChangeOrderStatusNotification extends Notification implements ShouldBroadcast
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
         return ['broadcast'];
     }

    public function toBroadcast($notifiable)
    {
        $status = optional($this->data)->order_status;
        return new BroadcastMessage([
            'order_id' => optional($this->data)->id,
            // 'chat_id' => optional(@$this->data->chats()->first())->id,
            'order_status' => optional($this->data)->order_status,
            'order_status_time' => optional($this->data->order_status_times)->$status,

            'driver' => optional($this->data)->driver,
            'driver_avatar' => optional(@$this->data->driver)->avatar,
            'route_to_show_driver' => $this->data->driver_id ? route('dashboard.driver.show',$this->data->driver_id) : '#',
            'route_to_edit_driver' => $this->data->driver_id ? route('dashboard.driver.edit',$this->data->driver_id) : '#',
            'driver_orders_count_trans' => trans('dashboard.order.order_count') . " : " . optional(optional($this->data->driver)->driverOrders)->count(),
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
            'body' => ['dashboard.notification.new_order_body',['body' => $this->data->client->fullname]],
            'created_at' => now()->isoFormat("D MMMM , Y ( h:mm a )"),
            'total_price' => $this->data->total_price,
            'order_id' => optional($this->data)->id,
            'order_status' => optional($this->data)->order_status,
            'notify_type' => 'order',
        ];
    }
}

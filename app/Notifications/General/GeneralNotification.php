<?php

namespace App\Notifications\General;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class GeneralNotification extends Notification implements ShouldBroadcast
{
    use Queueable;
    public $data;
    public $via_data;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data,$via_data=['database','broadcast'])
    {
        $this->data = $data;
        $this->via_data = $via_data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
     public function via($notifiable)
     {
         return $this->via_data;
     }

    public function toBroadcast($notifiable)
    {
        $title = "";
        if (isset($this->data['title'])) {
            if (!is_array($this->data['title'])) {
                $title = trans($this->data['title']);
            }elseif (isset($this->data['title'][1])) {
                $title = trans($this->data['title'][0],$this->data['title'][1]);
            }elseif (!isset($this->data['title'][1])) {
                $title = trans($this->data['title'][0]);
            }else{
                $title = "";
            }
        }
        $body = "";
        if (isset($this->data['body'])) {
            if (!is_array($this->data['body'])) {
                $body = trans($this->data['body']);
            }elseif (isset($this->data['body'][1])) {
                $body = trans($this->data['body'][0],$this->data['body'][1]);
            }elseif (!isset($this->data['body'][1])) {
                $body = trans($this->data['body'][0]);
            }else{
                $body = "";
            }
        }

        return new BroadcastMessage([
            'sender_id' => auth('api')->check() ? auth('api')->id() : (auth()->check() ? auth()->id() : null),
            'sender' => auth('api')->check() ? auth('api')->user()->toJson() : (auth()->check() ? auth()->user()->toJson() : null),
            'avatar' => auth('api')->check() ? auth('api')->user()->avatar : (auth()->check() ? auth()->user()->avatar : null),
            'title' => $title,
            'body' => $body,
            'route' => isset($this->data['route']) ? $this->data['route'] : '',
            'created_at' => date("Y-m-d h:i A"),
            'user_type' => $notifiable->user_type,
            'message_type' => isset($this->data['message_type']) ? $this->data['message_type'] : '',
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
            'sender' => auth('api')->check() ? auth('api')->user()->toJson() : (auth()->check() ? auth()->user()->toJson() : null),
            'title' => $this->data['title'],
            'notify_type' => isset($this->data['notify_type']) ? $this->data['notify_type'] : 'management',
            'body' => $this->data['body'],
            'route' => isset($this->data['route']) ? $this->data['route'] : '',
            'message_type' => isset($this->data['message_type']) ? $this->data['message_type'] : '',
            'driver_id' => isset($this->data['driver_id']) ? $this->data['driver_id'] : '',
        ];
    }
}

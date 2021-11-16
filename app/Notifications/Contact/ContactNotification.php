<?php

namespace App\Notifications\Contact;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class ContactNotification extends Notification implements ShouldBroadcast
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
            'contact_id' => $this->data->id,
            'user_id' => $this->data->user_id,
            'fullname' => $this->data->fullname,
            'email' => $this->data->email,
            'user_type' => $notifiable->user_type,
            'title' => trans('dashboard.contact.notification_message',['name' => $this->data->fullname]),
            'body' => str_limit($this->data->content,100),
            'route' => route('dashboard.contact.show',$this->data->id),
            'created_at' => date("Y-m-d h:i A"),
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
            'sender' => auth('api')->check() ? auth('api')->user()->toJson() : null,
            'title' => trans('dashboard.contact.notification_message',['name' => $this->data->fullname]),
            'body' => str_limit($this->data->content,100),
            'contact_id' => $this->data->id,
            'notify_type' => 'contact',
        ];
    }
}

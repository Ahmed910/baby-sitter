<?php

namespace App\Notifications\FCMNotifiations;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FcmApiNotifications extends Notification
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
        $this->data=$data;
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


    public function toDatabase($notifiable)
    {
        // dd($notifiable);
        return [
            'title'=> $this->data['title'],
            'body'=>  $this->data['body'],
            'sender_data'=> $this->data['sender_data'],
            'notify_type'         => 'management',
        ];
    }
}

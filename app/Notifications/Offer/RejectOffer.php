<?php

namespace App\Notifications\Offer;

use App\Http\Resources\Api\Notification\SenderResource;
use App\Models\Offer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RejectOffer extends Notification
{
    use Queueable;
    public $offer;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Offer $offer)
    {
        $this->offer = $offer;
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

        return [
            'title' => ['dashboard.notification.offer.offer_has_been_rejected_title'],
            'body' => ['dashboard.notification.offer.offer_has_been_rejected_body', ['body' => auth()->user()->name ?? auth()->user()->phone]],
            'sender_data' => new SenderResource(auth()->user()),
            'notify_type'=>'reject_offer',
            'offer_id' => optional($this->offer)->id,
        ];
    }

}

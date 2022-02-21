<?php

namespace App\Notifications\Orders;

use App\Http\Resources\Api\Notification\SenderResource;
use App\Models\MainOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RejectOrderNotification extends Notification implements ShouldBroadcast
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

    //  public function toFcm($notifiable)
    // {

    //     $message = new FcmMessage();
    //     $message->setHeaders([
    //         'project_id'    =>  "771078638305"   // FCM sender_id
    //     ])->content([
    //         'title'=>trans('dashboard.notification.order_has_been_rejected_title',[],$notifiable->current_lang),
    //         'body'=> trans('dashboard.notification.order_has_been_rejected_body',['body' => auth()->user()->name ?? auth()->user()->phone],$notifiable->current_lang),
    //         'sender_data' => new SenderResource(auth('api')->user()),
    //         'sound'        => '', // Optional
    //         'icon'         => '', // Optional
    //         'click_action' => '' // Optional
    //     ])
    //     ->data([
    //         'order_id'=>$this->booking->id,
    //         'type'=>$this->booking->to
    //     ])
    //     ; // Optional - Default is 'normal'.

    //     return $message;
    // }

    public function toDatabase($notifiable)
    {

        return [
            'title'=>['dashboard.notification.order_has_been_rejected_title'],
            'body'=> ['dashboard.notification.order_has_been_rejected_body'],
            'sender_data' => new SenderResource(auth('api')->user()),
            'notify_type'=>'change_order_status',
            'route' => route('dashboard.orders.show',$this->booking->id),
            'order_id' => optional($this->booking)->id,
        ];
    }

    public function toBroadcast($notifiable)
    {

        return new BroadcastMessage([
            'title'=>trans('dashboard.notification.order_has_been_rejected_title',[],$notifiable->current_lang),
            'body'=> trans('dashboard.notification.order_has_been_rejected_body',[],$notifiable->current_lang) . auth('api')->user()->name??auth('api')->user()->phone,
            'notify_type'=>'reject_order',
            'route' => route('dashboard.orders.show',$this->booking->id),
            'order_id' => optional($this->booking)->id,
        ]);
    }
}

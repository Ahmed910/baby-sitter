<?php

namespace App\Events\Chat;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $data;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $channels = [new PrivateChannel('babysitters-chat.'.$this->data->chat_id)];
        $admins = \App\Models\User::whereIn('user_type', ['superadmin','admin'])->get();
        foreach ($admins as $admin) {
            $channels[] =  new PrivateChannel('babysitters-notification.'.$admin->id);
        }
        return $channels;
    }


    public function broadcastWith()
    {
        return [
            'chat_id' => $this->data->chat_id,
            'order_id' => $this->data->order_id,
            'order_status' => $this->data->order->sitter_order->status,
            'message_position' => $this->data->message_position,
            'sender_data' => ['id' => $this->data->sender_id,'fullname' => optional($this->data->sender)->fullname,'image' => optional($this->data->sender)->avatar,'phone' => $this->data->sender->phone],
            'receiver_data' => ['id' => $this->data->receiver_id,'fullname' => optional($this->data->receiver)->fullname,'image' => optional($this->data->receiver)->avatar,'phone' => $this->data->receiver->phone],
            'message_sender' => $this->data->sender_id,
            'message_id' => $this->data->id,
            'message' => $this->data->message,
            'message_type' => $this->data->message_type,
            'location_img' => asset('dashboardAssets/images/icons/chat_map.jpg'),
            'created_at' => $this->data->created_at->format('Y-m-d H:i A'),
        ];
    }

    // public function broadcastAs()
    // {
    //     return 'NewMessage';
    // }
}

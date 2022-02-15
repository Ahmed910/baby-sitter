<?php

namespace App\Http\Resources\Api\Notification;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\{Order , Chat , MainOrder, Offer, OrderOffer};

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
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
        return [
            'id' => $this->id,
            'title' => isset($this->data['title']) ? $title : '',
            'body' => isset($this->data['body']) ? $body : '',
            'sender_data' => isset($this->data['sender_data']) ? $this->data['sender_data'] : '',
            'notify_type' => isset($this->data['notify_type']) ? $this->getNotifyType($this->data['notify_type']) : 'management',
            'order' => isset($this->data['order_id']) ? $this->orderData($this->data['order_id']) : null,
            'offer' => isset($this->data['offer_id']) ? $this->offerData($this->data['offer_id']) : null,
            'chat' => isset($this->data['chat_id']) ? $this->chatData($this->data['chat_id']) : null,
            'created_at' => $this->created_at->isoFormat("dddd , D MMMM , Y h:mm a "),
            'read_at' => optional($this->read_at)->format("Y-m-d H:i"),
            'image' => $this->getImageData($this->data)
        ];
    }

    protected function orderData($order_id)
    {
        $order = MainOrder::find($order_id);
        $data = null;
        if (isset($order) && $order) {
            $data = [
                'order_id' => $order->id,
                'client_id' => $order->client_id,
                'provider_id' => isset($order->center_id) ? $order->center_id : $order->sitter_id,
                'order_type' => $order->to,
                'order_status' => $order->to == 'sitter' ? optional($order->sitter_order)->status : optional($order->center_order)->status,
            ];
        }

        return $data;
    }

    protected function getNotifyType($notify_type)
    {
        switch ($notify_type) {
            case 'new_order':
                return 'order';
               break;

            case 'change_order_status':
                 return 'order';
                break;
            case 'change_offer_status':
                 return 'offer';
                break;

            case 'new_chat':
                 return 'chat';
                break;

            default:
                return 'management';
                break;
        }
    }

    protected function offerData($offer_id)
    {
        $offer = Offer::find($offer_id);
        $data = null;
        if ($offer) {
            $data = [
                'offer_id' => $offer->id,
                'offer_status' => $offer->status,
                'offer_price' => (string)$offer->offer_price,
                // 'reject_reason' => isset($reject_reason) ? $offer->reject_reason:,
            ];
        }

        return $data;
    }

    protected function chatData($chat)
    {
        $chat = Chat::find($chat);
        $data = null;
        if ($chat) {
            $data = [
                'chat_id' => $chat->id,
                'order_id' => $chat->order_id,
                'receiver_id' => ($chat->sender_id == auth('api')->id() ? $chat->receiver_id : $chat->sender_id),
            ];
        }

        return $data;
    }

    protected function getImageData($notify_data)
    {
        switch ($notify_data['notify_type']) {
            case 'new_order':
                $order = MainOrder::find($notify_data['order_id']);
                if(isset($order) && $order->to == 'sitter'){

                    $image = $order ? ($order->sitter_id == auth('api')->id() ? $order->client->avatar : ($order->client_id == auth('api')->id() && $order->sitter_id ? $order->sitter->avatar : setting('logo'))) : setting('logo');
                }elseif(isset($order) && $order->to == 'center'){

                    $image = $order ? ($order->center_id == auth('api')->id() ? $order->client->avatar : ($order->client_id == auth('api')->id() && $order->center_id ? $order->center->avatar : setting('logo'))) : setting('logo');
                }
                break;
            case 'change_order_status':
                $order = MainOrder::find($notify_data['order_id']);
                if(isset($order) && $order->to == 'sitter'){

                    $image = $order ? ($order->sitter_id == auth('api')->id() ? $order->client->avatar : ($order->client_id == auth('api')->id() && $order->sitter_id ? $order->sitter->avatar : setting('logo'))) : setting('logo');
                }elseif(isset($order) && $order->to == 'center'){

                    $image = $order ? ($order->center_id == auth('api')->id() ? $order->client->avatar : ($order->client_id == auth('api')->id() && $order->center_id ? $order->center->avatar : setting('logo'))) : setting('logo');
                }
                break;
            case 'change_offer_status':
                $offer = Offer::findOrFail($notify_data['offer_id']);
                $image= $offer->user->avatar;
                break;
            case 'new_chat':
                $chat = Chat::find($notify_data['chat_id']);
                $image = $chat ? ($chat->receiver_id == auth('api')->id() ? $chat->sender->avatar : $chat->receiver->avatar) : setting('logo');
                break;

            default:
                $image = setting('logo');
                break;
        }

        return $image;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $guarded = ['created_at','updated_at'];

	protected $dates = ['read_at'];

    public function setMessageAttribute($value)
	{
		if (request()->message_type == 'image') {
            $image = uploadImg($value,'chat');
            $this->attributes['message'] = $image;
		}elseif (in_array(request()->message_type ,['file','sound'])) {
            $file = uploadFile($value,'chat');
            $this->attributes['message'] = $file;
		}elseif (request()->message_type == 'order') {
            $this->attributes['message'] = MainOrder::find($value)->toJson();
        }else{
			$this->attributes['message'] = $value;
        }
	}

	public function getMessageAttribute()
	{
        if (in_array($this->message_type,['sound','file'])) {
			return asset('storage/files/chat/'.$this->attributes['message']);
		}elseif (in_array($this->message_type,['order','offer'])) {
			return json_decode($this->attributes['message']);
		}elseif ($this->message_type == 'image') {
            return asset('storage/images/chat/'.$this->attributes['message']);
		}else{
			return $this->attributes['message'] ?? '';
		}
	}

    public function sender()
    {
		return $this->belongsTo(User::class , 'sender_id');
	}

	public function receiver()
    {
		return $this->belongsTo(User::class , 'receiver_id');
	}

    public function chat()
    {
		return $this->belongsTo(Chat::class , 'chat_id');
	}

    public function order()
    {
		return $this->belongsTo(MainOrder::class , 'order_id');
	}
}

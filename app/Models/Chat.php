<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $guarded = ['created_at','updated_at'];

	protected $dates = ['read_at'];

    public function getLastMessageModelAttribute()
	{
		if (in_array($this->message_type,['sound','file'])) {
			return asset('storage/files/chat/'.$this->attributes['last_message']);
		}elseif (in_array($this->message_type,['order','offer'])) {
			return json_decode($this->attributes['last_message']);
		}elseif ($this->message_type == 'image') {
            return asset('storage/images/chat/'.$this->attributes['last_message']);
		}else{
			return $this->attributes['last_message'] ?? '';
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

	public function order()
    {
		return $this->belongsTo(MainOrder::class,'order_id');
	}

	public function messages()
    {
		return $this->hasMany(Message::class);
	}
}

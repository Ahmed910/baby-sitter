<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use Uuid;
    protected $guarded=['created_at','updated_at'];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

 
}

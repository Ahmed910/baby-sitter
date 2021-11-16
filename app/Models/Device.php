<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Device extends Model
{
    use Uuid;
    
    protected $guarded = ['created_at','updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $guarded = ['created_at','updated_at'];

    // public function sitter()
    // {
    //     return $this->belongsTo(User::class,'user_id');
    // }
}

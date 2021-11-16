<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Uuid;

class Permission extends Model
{
    use HasFactory, Uuid;

    protected $guarded = ['created_at','updated_at'];

    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }
}

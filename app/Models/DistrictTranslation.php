<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistrictTranslation extends Model
{
    use Uuid;

    public $timestamps = false;
    protected $guarded = ['created_at', 'updated_at'];
}

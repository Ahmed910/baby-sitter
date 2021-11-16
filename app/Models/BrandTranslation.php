<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class BrandTranslation extends Model
{
    use Uuid;
    public $timestamps = false;
    protected $guarded = ['created_at', 'updated_at'];
}

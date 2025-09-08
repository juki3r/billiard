<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OverrideTime extends Model
{
    protected $fillable = ['table_id', 'override_time'];
}

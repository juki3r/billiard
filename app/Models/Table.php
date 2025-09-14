<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Table extends Model
{
    use HasFactory;

    // Mass assignable fields
    protected $fillable = [
        'table_id',
        'time_remaining',
    ];
}

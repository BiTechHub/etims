<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionBreak extends Model
{
    protected $fillable = [
        'programme_id',
        'subtopic_id',
        'break_type',
        'date',
        'start_time',
        'end_time',
        'duration',
    ];

    
}

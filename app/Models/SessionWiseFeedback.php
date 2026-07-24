<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionWiseFeedback extends Model
{
    // 
    protected $fillable = [
        'session_id',
        'answer',
        'programme_id',
        'participant_id'
    ];

    public function sessions()
    {
        return $this->belongsTo(Subtopic::class , 'session_id' , 'id');
    }

    public function programme()
    {
        return $this->belongsTo(ProgrammeManagement::class , 'programme_id' , 'id');
    }


}

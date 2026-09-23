<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackResponse extends Model
{
     use HasFactory;
protected $table="feedbackrespones";
    protected $fillable = [
        'participant_id',
        'topic_id',
        'rating',
        'programme_id', 
    ];

    // Relationships
    public function participant()
    {
        return $this->belongsTo(Participant::class, 'participant_id');
    }

    public function submenu()
    {
        return $this->belongsTo(FeedbackSubmenu::class, 'submenu_id');
    }
}

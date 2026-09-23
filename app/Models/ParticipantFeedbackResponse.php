<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipantFeedbackResponse extends Model
{
    
protected $fillable = ['participant_id','program_id','feedback_question_id','chosen_answer','question_status'];
    
}

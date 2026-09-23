<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackMcqQuestionOption extends Model
{  
    protected $fillable =[
        'feedback_question_id',
        'option_1',
        'option_2',
        'option_3',
        'option_4',
        'correct_answer',
        
    ];

     public function feedbackQuestion()
    {
        return $this->belongsTo(
            FeedbackQusetion::class,
            'feedback_question_id'
            ,'id'
        );
    }
    




    
}

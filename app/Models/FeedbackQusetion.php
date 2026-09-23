<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackQusetion extends Model
{  
    protected $fillable =[
        'feedback_question_type_id',
        'question',
        'answer_type' // mcq , rating , text
    ];

     public function feedbackQuestionType()
    {
        return $this->belongsTo(
            FeedbackQusetionType::class,
            'feedback_question_type_id'
            ,'id'
        );
    }

     public function feedbackQuestionOptions()
    {
        return $this->hasMany(
            FeedbackMcqQuestionOption::class,
            'feedback_question_id'
            ,'id'
        );
    }






    
}

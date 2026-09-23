<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionPaperBasicDetail extends Model
{
    protected $fillable = [
        'programme_id',
        'total_marks',
        'passing_marks',
        'duration',
        'exit_exam_date',
        'entry_exam_date',
        'entry_exam_time',
        'exit_exam_time',
        'exam_type',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamPaper extends Model
{
    protected $table="exam_papers";
        protected $fillable = [
        'department_id',
        'programme_id',
        'question_id',
     
    ];
}

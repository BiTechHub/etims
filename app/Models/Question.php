<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['department_id', 'question_title', 'right_option', 'option_A', 'option_B', 'option_C', 'option_D','programme_id']; 
}

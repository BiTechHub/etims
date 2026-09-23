<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtopic extends Model
{
    use HasFactory;

    // Specify the table name if it's not the plural form of the model name
    protected $table = 'subtopics';

    // Specify the fields that can be mass-assigned (fillable)
    protected $fillable = [
        'programme_id',
        'session_name',
        'title',
        'date',
        'start_time',
        'end_time',
        'faculty_id',
        'faculty_type',
        'is_break',
        'break_type',
        'duration'
    ];

    // Define the relationship with the Programme model
    public function programme()
    {
        return $this->belongsTo(ProgrammeManagement::class);
    }

    // Define the relationship with the Faculty (User) model
    public function faculty()
    {
        return $this->belongsTo(User::class, 'faculty_id');
    }

    public function SessionBreaks()
    {
        return $this->hasMany(SessionBreak::class , 'subtopic_id' , 'id');
    }
}

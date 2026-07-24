<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramCalendar extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'calendar_year',
        'agency_group_id',
        'program_code',
        'program_title',
        'program_date',
        'program_to',
        'status',
        'location',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'program_date' => 'date',
        'program_to' => 'date',
    ];

    /**
     * Get the agency group associated with the program calendar.
     */
    public function agencyGroup()
    {
        return $this->belongsTo(AgencyGroup::class);
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    
}
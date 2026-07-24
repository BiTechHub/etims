<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyGroup extends Model
{
    use HasFactory;



    protected $fillable = ['code', 'name', 'is_active']; // Allow mass assignment
    
    public function agencyTypes()
    {
        return $this->hasMany(AgencyType::class);
    }
    public function programCalendars()
    {
        return $this->hasMany(ProgramCalendar::class);
    }
}

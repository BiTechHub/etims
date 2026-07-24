<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faculity extends Model
{
    protected $table = 'faculities';
    protected $fillable = [ 'id','department_id','name','is_active'];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function programmesAsFaculty1(): HasMany
    {
        return $this->hasMany(ProgrammeManagement::class, 'faculty1_id');
    }

    // Relationship for second faculty
    public function programmesAsFaculty2(): HasMany
    {
        return $this->hasMany(ProgrammeManagement::class, 'faculty2_id');
    }
}

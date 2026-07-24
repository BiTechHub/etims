<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgrammeManagementArchive extends Model
{
    protected $table = 'programmes_archive';

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function sponsor()
    {
        return $this->belongsTo(Sponsor::class, 'sponsor_id');
    }
}

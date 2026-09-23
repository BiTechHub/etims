<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    use HasFactory;

    protected $table = 'programmes';

    protected $fillable = [
        'agency_group_id',
        'name',
        'target_group',
        'duration',
        'content',
        'objective',
        'is_active'
    ];

    // Relationship to AgencyGroup
    public function agencyGroup()
    {
        return $this->belongsTo(AgencyGroup::class, 'agency_group_id');
    }

    // Relationship to Participant nominations (direct link)
    public function participantNominations()
    {
        return $this->hasMany(Participant::class);
    }

    // Relationship to nominations through participants
    public function nominations()
    {
        return $this->hasManyThrough(
            Nomination::class,             // Final model (Nomination)
            Participant::class,            // Intermediate model (Participant)
            'programme_id',                // Foreign key on the participants table
            'id',                          // Primary key on the nominations table
            'id',                          // Primary key on the programmes table
            'nomination_id'                // Foreign key on the participants table
        );
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgrammeAgencyFee extends Model
{
    protected $table = 'programme_agency_fees';

    protected $fillable = [
        'programme_id',
        'agency_type_id',
        'fee',
    ];

    // If you want to define relationships (optional, remove if no foreign keys):
    public function programme()
    {
        return $this->belongsTo(ProgrammeManagement::class, 'programme_id');
    }

    public function agencyType()
    {
        return $this->belongsTo(AgencyType::class, 'agency_type_id');
    }
}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nomination extends Model
{
    protected $fillable = [
        'agency_type_id',
        'agency_id',
        'nomination_date',
        'rate_per_person',
        'programme_id',
        'status',
        'total',
    ];

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function agencyType()
    {
        return $this->belongsTo(AgencyType::class);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }
    public function programme()
{
    return $this->belongsTo(ProgrammeManagement::class);
}
}

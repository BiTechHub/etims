<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnouncementLog extends Model
{
    protected $table = 'announcement_logs';

    protected $fillable = [
        'programme_id',
        'agency_type_id',
        'email',
        'subject',
        'message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function programme()
    {
        return $this->belongsTo(ProgrammeManagement::class, 'programme_id');
    }

    public function agencyType()
    {
        return $this->belongsTo(AgencyType::class, 'agency_type_id');
    }
}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $table = "nomination_participants";
    protected $fillable = ['nomination_id', 'title', 'name', 'email', 'city', 'state', 'phone', 'designation','programme_id','token_id', 'checkout_time','date','gender','rooms_id','bed_id','status',
    'attendence','checked_in_at','response_id','agency_id',


];

    public function nomination()
    {
        return $this->belongsTo(Nomination::class);
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    public function bed()
{
    return $this->hasOne(BedAllocation::class);
}

public function feedbackResponses()
{
    return $this->hasMany(FeedbackResponse::class, 'participant_id');
}
}

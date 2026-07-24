<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BedAllocation extends Model
{
    use HasFactory;
    protected $fillable = [
        'block_id', 'room_number', 'bed_number','participant_id','programme_id','status','is_available','room_id'
    ];

    // Relationship with Block model (assuming it exists)
    public function block()
    {
        return $this->belongsTo(Block::class);
    }

    public function participant()
{
    return $this->belongsTo(Participant::class);
}

public function room()
{
    return $this->belongsTo(Room::class , 'room_id' );
}
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{

    protected $table="roommanages";
    protected $fillable=['room_number','block_id','room_type','is_available','is_active'];


public function block()
{
    return $this->belongsTo(Block::class, 'block_id');
}
public function type()
{
    return $this->belongsTo(Typesofroom::class, 'room_type');
}

public function bed_allocation()
{
    return $this->hasMany(BedAllocation::class , 'room_id');
}
}

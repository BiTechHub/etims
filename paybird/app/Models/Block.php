<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $table="blocks";
    protected $fillable=['name'];

    

    public function room()
{
    return $this->belongsTo(Room::class, 'block_id');
}
}

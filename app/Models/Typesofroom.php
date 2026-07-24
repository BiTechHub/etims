<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Typesofroom extends Model
{
     protected $table="types_of_rooms";
     protected $fillable=['types_of_rooms','avaible_beds'];


     public function rooms()
     {
         return $this->hasMany(Room::class, 'room_type');
     }
}

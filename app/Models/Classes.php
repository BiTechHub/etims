<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classes extends Model
{
    use HasFactory;
  


    protected $fillable = [ 'name', 'is_active']; 

   public function programmes(): HasMany
{
    return $this->hasMany(ProgrammeManagement::class, 'class_room_id');
}
}

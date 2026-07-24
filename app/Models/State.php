<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
      use HasFactory;

    protected $fillable = ['name'];

    // A state has many districts
    public function districts()
    {
        return $this->hasMany(District::class);
    }
}

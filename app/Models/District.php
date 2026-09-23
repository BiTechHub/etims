<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
        use HasFactory;

    protected $fillable = ['name', 'state_id'];

    // A district belongs to a state
    public function state()
    {
        return $this->belongsTo(State::class);
    }
}

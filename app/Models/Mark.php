<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    protected $table="marks";

    protected $fillable=['marks','programme_id','participants_id','entry_test','exist_test'];



    public function participant()
{
    return $this->belongsTo(Participant::class, 'participants_id');
}
}

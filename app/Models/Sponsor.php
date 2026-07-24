<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sponsor extends Model
{
    protected $table='sponsors';
    protected $fillable = [ 'id','name', 'is_active','hindi_name','is_deleted'];

    public function programmes(): HasMany
    {
        return $this->hasMany(ProgrammeManagement::class);
    }
}

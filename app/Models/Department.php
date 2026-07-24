<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;
    protected $fillable = [ 'name', 'is_active','is_deleted']; // Allow mass assignment
    public function programmes(): HasMany
    {
        return $this->hasMany(ProgrammeManagement::class);
    }
}

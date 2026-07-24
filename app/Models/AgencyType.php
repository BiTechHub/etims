<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyType extends Model
{
    use HasFactory;

    // Table name (optional, if different from default "agency_types")
    protected $table = 'agency_types';

    // Mass assignable attributes
    protected $fillable = [
      
        'name','hindi_name','is_deleted',
    ];

    /**
     * Get the agency group that this agency type belongs to.
     */

public function agencies()
{
    return $this->hasMany(Agency::class);
}
}

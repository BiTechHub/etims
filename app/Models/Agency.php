<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Make sure this is imported
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agency extends Authenticatable
{
    use HasFactory;

    protected $table = 'agencies';

    protected $fillable = [
        'agency_type_id',
        'name',
        'sponsor_bank',
        'chairman',
        'address',
        'state',
        'city',
        'pincode',
        'phone',
        'emailid',
        'country',
        'fax',
        'cc_email',
        'is_active',
        'password',
        'user_name',
        'password',
        'token',
        'deleted_at',
      'approved',
      'designation',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function agencyType()
    {
        return $this->belongsTo(AgencyType::class, 'agency_type_id');
    }

    // Ensure you have the necessary methods for authentication, although they should be inherited from Authenticatable
    // Example:
    // public function getAuthIdentifierName()
    // {
    //     return 'emailid'; // Or 'id' based on your database column for login
    // }
}

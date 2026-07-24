<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'users'; // Table name

    protected $fillable = [
      'user_type',
        'username',
        'name',
        'email',
        'dob',
        'designation',
        'address',
        'state',
        'city',
        'pincode',
        'phone',
        'image',
        'password',
        'hindi_name',
        'deleted_at',
       'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

 

    /**
     * Relationship: User belongs to a UserType
     */
  
    public function programCalendor()
    {
        return $this->hasMany(ProgramCalendar::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegisterUser extends Model
{
    protected $table = 'register_user';
    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone',
        'organization', 'job_title', 'country', 'password'
    ];
}
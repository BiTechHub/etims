<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $guard = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'user_name',
        'permissions',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'permissions' => 'array',   
    ];

    /**
     * Check if this admin has a specific permission on a module
     */


public function hasAccess($module, $action = 'view')
{
    
    if (strtolower($this->role) === 'admin') {
        return true;
    }

   return isset($this->permissions[$module][$action]) 
        && $this->permissions[$module][$action] == 1;
}
}
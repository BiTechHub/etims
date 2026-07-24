<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    use HasFactory;

    protected $table = 'users_type'; // Table name

    protected $fillable = ['name', 'description']; // Fillable columns

    public function users()
    {
        return $this->hasMany(User::class, 'user_type_id');
    }
}

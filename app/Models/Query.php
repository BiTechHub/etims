<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;  // Add this line if you plan to use factories
use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    use HasFactory;  // Add this line if you're using Laravel factories for testing or seeding

    protected $table = 'hostel_queries';  // This is correct if the table name is custom

    protected $fillable = [
        'name',
        'phone',
        'email',      
        'room',
        'message',
    ];

    // If your table doesn't have the default timestamp columns (created_at, updated_at),
    // set the following property to false:
    // public $timestamps = false;
}

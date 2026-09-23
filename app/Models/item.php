<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class item extends Model
{
    use HasFactory;
    protected $table = 'itemshead';
    protected $fillable = ['unit', 'name', 'is_active']; // Allow mass assignment
}

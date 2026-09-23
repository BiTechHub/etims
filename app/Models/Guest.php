<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'designation',
        'dob',
        'phone',
        'email',
        'address',
        'state',
        'city',
        'pincode',
        'account_no',
        'ifsc',
        'branch_name',
        'bank_address',
        'bank_name',
	'acount_holder_name',
        'kyc',
        'is_active',
        'is_delete',
        'specialization',
        'cv',
    ];
}

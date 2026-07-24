<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackModel extends Model
{

    protected $table="hostel_feedbacks";
    protected $fillable = [
        'name', 'email', 'phone',
        'check_out_experience', 'cleanliness', 'housekeeping',
        'staff_service', 'restaurant_food', 'amenities',
        'overall_rating', 'comments','programme_id',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedbacksubmenu extends Model
{
       use HasFactory;

    protected $fillable = ['menu_id', 'name','response_type'];

    public function feedbackMenu()
    {
        return $this->belongsTo(Feedbackmenu::class);
    }

    public function feedbackResponses()
{
    return $this->hasMany(FeedbackResponse::class, 'submenu_id');
}
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedbackmenu extends Model
{
       protected $table="feedbackmenus";

    protected $fillable=['name'];


       public function submenus()
    {
        return $this->hasMany(Feedbacksubmenu::class,'menu_id');
    }
}

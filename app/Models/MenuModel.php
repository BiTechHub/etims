<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuModel extends Model
{
    protected $table="menus";

    protected $fillable=['name'];


       public function submenus()
    {
        return $this->hasMany(Submenu::class,'menu_id');
    }
}

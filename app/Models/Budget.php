<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
     protected $fillable = [
        'programme_id',
        'menu_id',
        'submenu_id',
        'price',
        'quantity',
        'total',
    ];

    public function programme() {
        return $this->belongsTo(Programme::class);
    }

    public function menu() {
        return $this->belongsTo(MenuModel::class);
    }

    public function submenu() {
        return $this->belongsTo(Submenu::class);
    }
}

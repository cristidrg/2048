<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $attributes = [
        'obstacleCount' => 1,
    ];

    public function blocks()
    {
        return $this->hasMany('App\Block');
    }
}

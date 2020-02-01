<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $attributes = [
        'obstacleCount' => 1,
    ];

    public function getPlayingState()
    {
        foreach($this->blocks as $block)
        {
            if ($block->value == 2048) {
                return 'win';
            } else if ($block->value == 0) {
                return  'playing';
            }
        }

        return 'lost';
    }
    
    public function blocks()
    {
        return $this->hasMany('App\Block');
    }
}

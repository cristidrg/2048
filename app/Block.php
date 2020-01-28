<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    public $attributes = [
        'row' => '',
        'column' => '',
        'value' => ''
    ];
}

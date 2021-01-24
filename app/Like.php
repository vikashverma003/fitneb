<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Like extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'likes';
}
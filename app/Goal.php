<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Goal extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'goals';
}
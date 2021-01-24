<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class WorkoutInterest extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'workout_interest';
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class WorkoutDetail extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'workout_details';
}
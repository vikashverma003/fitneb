<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class TrainingDetail extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'training_details';
}
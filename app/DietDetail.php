<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class DietDetail extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'diets_details';
}
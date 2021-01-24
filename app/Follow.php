<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Follow extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'follows';
}
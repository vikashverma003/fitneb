<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class MyYoga extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'myyoga';
}
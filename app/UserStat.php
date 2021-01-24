<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class UserStat extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'user_stats';
}
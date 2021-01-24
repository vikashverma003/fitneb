<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Notification extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'notifications';
}
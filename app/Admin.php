<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Admin extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'admin';
}
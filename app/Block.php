<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Block extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'blocks';
}
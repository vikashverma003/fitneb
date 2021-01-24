<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class LikeGymPartner extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'like_gym_partner';
}
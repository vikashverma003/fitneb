<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class FavoriteGymPartner extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'favorite_gym_partner';
}
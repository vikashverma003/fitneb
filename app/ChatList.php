<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class ChatList extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'chat_list';
}
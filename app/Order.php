<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use App\User;

class Order extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'orders';

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

}

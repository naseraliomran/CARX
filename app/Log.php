<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{

    protected $table='logs';
    protected $fillable=[
        'userID',
        'color',
        'model',
        'counter',
        'query',

    ];

    protected $hidden=[
        'updated_at',
        'created_at'
    ];

}

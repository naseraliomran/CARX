<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class rate extends Model
{

    protected $table='rates';
    protected $fillable=[
        'workShopID',
        'userID',
        'rate',

    ];

    protected $hidden=[
        'updated_at',
        'created_at'
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $table='orders';
    protected $fillable=[
        'userID',
        'carPartID',
        'storeID',
        'confirm',

    ];

    protected $hidden=[
        'updated_at',
        'created_at'
    ];

    public function CarParts(){
        return $this->belongsTo('App\CarParts','carPartID');
    }

}

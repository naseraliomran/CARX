<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarParts extends Model
{
    protected $table='car_parts';
    protected $fillable=[
        'storeID',
        'userID',
        'partName',
        'manufacturingCountry',
        'partPrice',
        'Quantity',
        'imagPart',


    ];

    protected $hidden=[
        'updated_at',
        'created_at'
    ];

    public function Store(){
        return $this->belongsTo('App\Store','storeID');
    }
    public function User(){
        return $this->belongsTo('App\User','userID');
    }



}

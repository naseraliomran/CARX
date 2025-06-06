<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $table='stores';
    protected $fillable=[
        'storeName',
        'storeAddress',
        'companyID',
        'phoneNumber',
        'freeze',
        'city'

    ];

    protected $hidden=[
        'updated_at',
        'created_at'
    ];
    public function Salesman(){
        return $this->hasMany('App\Salesman','storeID');
    }

    public function CarParts(){
        return $this->hasMany('App\CarParts','storeID');
    }

    public function Sales(){
        return $this->hasMany('App\Sales','storeID');
    }
}

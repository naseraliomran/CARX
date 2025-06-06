<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SoldCars extends Model
{

    protected $table='sold_cars';
    protected $fillable=[
        'carID',
        'buyerName',
        'buyersPhoneNumber',
    ];

    protected $hidden=[
        'updated_at',
        'created_at'
    ];
    public function Car(){
        return $this->belongsTo('App\Car','carID');
    }
}

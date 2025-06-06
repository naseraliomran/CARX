<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $table='cars';
    protected $fillable=[
        'idUser',
        'companyID',
        'state',
        'name',
        'describe',
        'manufacturingYear',
        'price',
        'color',
        'city',
        'mileage',
        'engineCapacity',
        'model',
        'carMode',
        'motionVector',
        'isBooking'

    ];

    protected $hidden=[
        'updated_at',
        'created_at'
    ];

//    public function favorite(){
//        return $this->belongsTo('App\Car', 'idFavoriteObject');
//    }

    public function imageCar(){
        return $this->hasMany('App\img_cars', 'idCar');
    }

    public function SoldCars(){
        return $this->hasOne('App\SoldCars','carID');
    }
    public function RentalCars(){
        return $this->hasOne('App\RentalCars','carID');
    }

}

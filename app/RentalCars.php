<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RentalCars extends Model
{
    protected $table='rental_cars';
    protected $fillable=[
        'carID',
        'tenantName',
        'tenantPhoneNumber',
        'bookingPeriod',

    ];

    protected $hidden=[
        'updated_at',
        'created_at'
    ];

    public function Car(){
        return $this->belongsTo('App\Car','carID');
    }
}

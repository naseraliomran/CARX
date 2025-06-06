<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bookings extends Model
{
    protected $table='bookings';
    protected $fillable=[
        'userID',
        'carID',
        'confirm',


    ];

    protected $hidden=[
        'updated_at',
        'created_at'
    ];

    public function User(){
        return $this->belongsTo('App\User','userID');
    }

    public function Car(){
        return $this->belongsTo('App\Car','carID');
    }
}

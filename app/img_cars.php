<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class img_cars extends Model
{
    protected $table='img_cars';
    protected $fillable=[
        'idCar',
        'imageName',

    ];

    protected $hidden=[
        'updated_at',
        'created_at'
    ];

    public function car(){
        return $this->belongsTo('App\Car');
    }
}

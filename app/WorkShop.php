<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkShop extends Model
{
    protected $table='work_shops';
    protected $fillable=[
        'name',
        'phone',
        'address',
        'workingTimeFrom',
        'workingTimeTo',
        'available',
        'address_address',
        'address_latitude',
        'address_longitude',
        'rate',
        'workshopOwnerID'


    ];

    protected $hidden=[
        'updated_at',
        'created_at'
    ];
    public function WorkshopOwner(){
        return $this->belongsTo('App\User',"workshopOwnerID");
    }

}

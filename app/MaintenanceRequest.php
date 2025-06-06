<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{
    protected $table='maintenance_requests';
    protected $fillable=[
        'userID',
        'description',
        'confirm',
        'address_latitude',
        'address_longitude',
        'workshopID'


    ];

    protected $hidden=[
        'updated_at',
        'created_at'
    ];
}





















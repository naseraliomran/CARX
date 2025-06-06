<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table='employees';
    protected $fillable=[
        'userID',
        'companyID',

    ];

    protected $hidden=[
        'updated_at',
        'created_at'
    ];
    public function User(){
        return $this->belongsTo('App\User','userID');
    }

    public function EmployeeCompany(){
        return $this->belongsTo('App\CarsCompany','companyID');
    }
}

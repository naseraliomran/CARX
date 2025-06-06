<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarsCompany extends Model
{
    protected $table='cars_companies';
    protected $fillable=[
        'nameCompany',
        'code',
        'companyDirectorID',
        'freeze'

    ];

    protected $hidden=[
        'updated_at',
        'created_at'
    ];
    public function CompanyDirector(){
        return $this->belongsTo('App\User',"companyDirectorID");
    }

}

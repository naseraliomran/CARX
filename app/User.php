<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject

{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
//     */


    protected $fillable = [
        'name', 'age', 'password','mobile','jop','gender'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @inheritDoc
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @inheritDoc
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function CompanyDirector(){
        return $this->hasOne('App\CarsCompany',"companyDirectorID");
    }

    public function Employee(){
        return $this->hasOne('App\Employee',"userID");
    }
    public function Salesman(){
        return $this->hasOne('App\Salesman',"userID");
    }

    public function CarPrats(){
        return $this->hasMany('App\CarParts','userID');
    }


    public function Permission(){
        return $this->hasOne('App\Permission','userID');
    }


    public function WorkShopOwner(){
        return $this->hasOne('App\WorkShop','workshopOwnerID');
    }

}

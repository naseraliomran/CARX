<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table='permissions';
    protected $fillable=[
        'userID',
        'permissionName',

    ];

    protected $hidden=[
        'updated_at',
        'created_at'
    ];

    public function User(){
        return $this->belongsTo('App\User','userID');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salesman extends Model
{
    protected $table='salesmen';
    protected $fillable=[
        'userID',
        'storeID',
    ];

    protected $hidden=[
        'updated_at',
        'created_at'
    ];

    public function Store(){
        return $this->belongsTo('App\Store');
    }
    public function SalesmanUser(){
        return $this->belongsTo('App\User');
    }
}

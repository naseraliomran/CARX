<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $table='sales';
    protected $fillable=[
        'storeID',
        'carPartID',
        'quantity',
        'salesDate',
        'returnedPieceQuantity',

    ];

    protected $hidden=[
        'updated_at',
        'created_at'
    ];

    public function Store(){
        return $this->belongsTo('App\Store','storeID');
    }

    public function CarParts(){
        return $this->belongsTo('App\CarParts','carPartID');
    }

}

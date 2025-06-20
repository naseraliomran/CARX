<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $table = 'subscription_plans';
    protected $fillable = [
        'name',
        'ads_limit',
        'duration_days',
        'price'
    ];
    public $timestamps = false;
}

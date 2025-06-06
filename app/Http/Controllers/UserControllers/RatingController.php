<?php

namespace App\Http\Controllers\UserControllers;

use App\Http\Controllers\Controller;
use App\rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function rate(Request $request){
        $userID=Auth::user()->id;
        $rate=rate::create([
            'workShopID'=>$request->input('workShopID'),
            'userID'=>$userID,
            'rate'=>$request->input('rate'),
        ]);
        return response()->json($rate,200);

    }
}

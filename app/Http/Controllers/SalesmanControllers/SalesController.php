<?php

namespace App\Http\Controllers\SalesmanControllers;

use App\CarParts;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Sales;
use Carbon\Carbon;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
     public function Sale(Request $request,$partID){
         try {
              $storeID=Auth::user()->Salesman->storeID;
              $carPart=CarParts::find($partID);
              if(!$carPart){
                  return response()->json('Quantity over',404);
              }

             if($carPart->Quantity<$request->input('quantity'))
              {
                  return response()->json($carPart->Quantity,200);
              }
              $findSalePart=Sales::where('carPartID',$partID)
               ->whereDate('salesDate', Carbon::today()) ->get();
              if(count($findSalePart)!=0){
                  foreach ($findSalePart as $item)
                      $item->update([
                      'quantity'=>$item->quantity+$request->input('quantity'),
                  ]);
                  $Quantity= $carPart->Quantity-$request->input('quantity');
                  $carPart->update([
                      'Quantity' => $Quantity
                  ]);

                  return response()->json($findSalePart,200);
              }
              $sale=Sales::create([
                  'storeID'=>$storeID,
                  'carPartID'=>$carPart->id,
                  'quantity'=>$request->input('quantity'),
                  'salesDate'=>now()->toDateTimeString(),
                  'returnedPieceQuantity'=>0,
              ]);
              $Quantity= $carPart->Quantity-$request->input('quantity');
             $carPart->update([
                 'Quantity' => $Quantity
             ]);
             if($Quantity<=4){
                 return response()->json(4,200);

             }

             return response()->json($sale,200);
         }catch (Exception $exception){
             return response()->json("Error, Please make sure you are connected to the
            internet",500);
         }
     }
}

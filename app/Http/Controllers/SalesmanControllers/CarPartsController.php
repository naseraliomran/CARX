<?php

namespace App\Http\Controllers\SalesmanControllers;

use App\CarParts;
use App\Http\Controllers\Controller;
use App\Notifications\InvoicePaid;
use App\Order;
use App\Store;
use App\User;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarPartsController extends Controller
{
    public function savePart(Request $request){
        try {
             $salesman=Auth::user();
             $image=$this->saveImages($request->imagPart,'images/CarPartsPictures');
             $carPart=CarParts::create([
                 'storeID'=>$salesman->Salesman->storeID,
                 'userID'=>$salesman->id,
                 'partName'=>$request->input('partName'),
                 'manufacturingCountry'=>$request->input('manufacturingCountry'),
                 'partPrice'=>$request->input('partPrice'),
                 'Quantity'=>$request->input('Quantity'),
                 'imagPart'=>$image,
             ]);
             return response()->json($carPart,200);
        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }

    }

    public function updatePart(Request $request,$partID){
        try {

            $carPart=CarParts::find($partID);
          $newCarPartQuantity=$carPart->Quantity;
            if(!$carPart){
                return response()->json('Not found part',404);
            }
            $image=$this->saveImages($request->imagPart,'images/CarPartsPictures');
            $carPart->update([
                'partName'=>$request->input('partName'),
                'manufacturingCountry'=>$request->input('manufacturingCountry'),
                'partPrice'=>$request->input('partPrice'),
                'Quantity'=>$request->input('Quantity'),
                'imagPart'=>$image,
            ]);

            if($carPart->Quantity!=$newCarPartQuantity)
            {
                $orders=Order::where('carPartID',$partID)->where('confirm',0)->get();
                $srote=Store::find($carPart->storeID);

                foreach ($orders as  $order){

                     $user=User::find($order->userID);

                     $message=[
                         'storeName'=>$srote->storeName,
                         'carPartID'=>$partID,
                     ];

                    $user->notify(new InvoicePaid($message));
                    $order->update([
                        'confirm'=>1
                    ]);
                }
            }
            return response()->json($carPart,200);
        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }
    }

    public function deletePart($partID){
        try {
            $carPart=CarParts::find($partID);
            if(!$carPart){
                return response()->json('Not found part',404);
            }
            $carPart->delete();
            return response()->json('Part Deleted',200);
        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }
    }

    public function getPartInMyStore(){
        try {
            $storeID=Auth::user()->Salesman->storeID;
           $CarParts=Store::find($storeID)->CarParts;
           if(!$CarParts){
               return response()->json('there is no Car Parts in this store');
           }
           return response()->json($CarParts,200);
        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }
    }

    public function saveImages($photo,$folder)
    {
        $file_extension=  $photo -> getClientOriginalExtension();
        $file_name = time().'.'.$file_extension;
        $file_path= $folder;
        $photo -> move($file_path,$file_name);
        return $file_name;
    }
}

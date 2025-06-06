<?php

namespace App\Http\Controllers\UserControllers;

use App\CarParts;
use App\Http\Controllers\Controller;
use App\Order;
use App\Sales;
use App\Store;
use App\User;
use Carbon\Carbon;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarPartsController extends Controller
{
    public function savePart(Request $request){
        try {
             $user=Auth::user();
             $image=$this->saveImages($request->imagPart,'images/CarPartsPictures');
             $carPart=CarParts::create([
                 'storeID'=>0,
                 'userID'=>$user->id,
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
    public function search(Request $request){
        try {
            $carPart=CarParts::where('partName',$request->input('partName'))->get();
            if(count($carPart)==0){
                return response()->json('not found result',200);
            }
            return response()->json($carPart,200);

        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }

    }

    public function filtering(Request $request){
        if($request->partName!=null && $request->manufacturingCountry!=null &&
            $request->pricefrom!=null &&$request->priceto!=null )
        {
            $cars=CarParts::where('partName','=',$request->partName)
                ->where('manufacturingCountry','=',$request->manufacturingCountry)
                ->whereBetween('partPrice',[$request->pricefrom,$request->priceto])
                ->get();
            if(count($cars)==0){
                return response()->json("not found Car",404);
            }

            return $cars;
        }

        if($request->partName!=null && $request->manufacturingCountry!=null &&
            $request->pricefrom==null &&$request->priceto==null )
        {
            $cars=CarParts::where('partName','=',$request->partName)
                ->where('manufacturingCountry','=',$request->manufacturingCountry)
                ->get();
            if(count($cars)==0){
                return response()->json("not found Car",404);
            }

            return $cars;
        }

        if($request->partName!=null && $request->manufacturingCountry==null &&
            $request->pricefrom!=null &&$request->priceto!=null )
        {
            $cars=CarParts::where('partName','=',$request->partName)
                ->whereBetween('partPrice',[$request->pricefrom,$request->priceto])
                ->get();
            if(count($cars)==0){
                return response()->json("not found Car",404);
            }

            return $cars;
        }

        if($request->partName==null && $request->manufacturingCountry!=null &&
            $request->pricefrom!=null &&$request->priceto!=null )
        {
            $cars=CarParts::where('manufacturingCountry','=',$request->manufacturingCountry)
                ->whereBetween('partPrice',[$request->pricefrom,$request->priceto])
                ->get();
            if(count($cars)==0){
                return response()->json("not found Car",404);
            }

            return $cars;
        }

        if($request->partName==null && $request->manufacturingCountry==null &&
            $request->pricefrom!=null &&$request->priceto!=null )
        {
            $cars=CarParts::whereBetween('partPrice',[$request->pricefrom,$request->priceto])
                ->get();
            if(count($cars)==0){
                return response()->json("not found Car",404);
            }

            return $cars;
        }

        if($request->partName!=null && $request->manufacturingCountry==null &&
            $request->pricefrom==null &&$request->priceto==null )
        {
            $cars=CarParts::where('partName','=',$request->partName)
                ->get();
            if(count($cars)==0){
                return response()->json("not found Car",404);
            }

            return $cars;
        }

        if($request->partName==null && $request->manufacturingCountry!=null &&
            $request->pricefrom==null &&$request->priceto==null )
        {
            $cars=CarParts::where('manufacturingCountry','=',$request->manufacturingCountry)
                ->get();
            if(count($cars)==0){
                return response()->json("not found Car",404);
            }

            return $cars;
        }

        if($request->partName==null && $request->manufacturingCountry==null &&
            $request->pricefrom==null &&$request->priceto==null )
        {
            return response()->json("Please Select Value for Filtering","200");
        }

        return response()->json("Please Select Value for Filtering","200");

    }

    public function viewAllPartsCar(){
        try {
            $carParts=CarParts::all();
            if(count($carParts)==0)
            {
                return response()->json('not found car Parts');
            }
            return response()->json($carParts,200);
        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }
    }


    public function updatePart(Request $request,$partID){
        try {

            $carPart=CarParts::find($partID);
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

    public function getMyParts(){
        try {
            $user=Auth::user();

           $CarParts=CarParts::where('userID',$user->id)->get();
           if(count($CarParts)==0){
               return response()->json('there is no Car Parts ..');
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

    public  function allMissingCarParts(){

        $missingCarParts=CarParts::where('storeID','<>',0)
            ->where('Quantity',0)->get();
        if(count($missingCarParts)==0){
            return response()->json('Not Found Missing Car Parts.. ',200);
        }
        return response()->json($missingCarParts,200);
    }

    public function sendOrder($carPartID,$storeID){
        $carPart=CarParts::find($carPartID);
        if(!$carPart){
            return response()->json('This Car part not available');
        }
       $userID=Auth::user()->id;
        $order=Order::create([
                    'userID'=>$userID,
                    'carPartID'=>$carPartID,
                    'storeID'=>$storeID,
                    'confirm'=>0,
        ]);
        return response()->json($order,200);
    }

    public function myOrder(){
        $userID=Auth::user()->id;
        $myOrder=Order::where('userID',$userID)->with('CarParts')->get();
        if(count($myOrder)==0){
            return response()->json('No Items');

        }
        return response()->json($myOrder,200);
    }

    public function deleteOrder($orderID){
        $order=Order::find($orderID);
        if(!$order){
            return response()->json('not found Order');
        }
        $order->delete();
        return response()->json('Order Deleted ...');
    }


}

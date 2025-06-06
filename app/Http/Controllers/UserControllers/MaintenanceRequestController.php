<?php

namespace App\Http\Controllers\UserControllers;

use App\Http\Controllers\Controller;
use App\MaintenanceRequest;
use App\Notifications\SendMaintenanceRequest;
use App\Permission;
use App\rate;
use App\User;
use App\WorkShop;
use Carbon\Carbon;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceRequestController extends Controller
{

    public function getWorkShops(Request $request){
        try {
            $workShopDistance=[];
            $latitudeFrom=$request->input('latitude');
            $longitudeFrom= $request->input('longitude');
            $mytime = now();

            $workShops = WorkShop::where('workingTimeFrom','<=',$mytime->toTimeString())
                ->where('workingTimeTo','>=',$mytime->toTimeString())->get();
            if(count($workShops)==0){
            return response()->json('no Workshop available..');
            }
            foreach ($workShops as $workShop){
                $theta = $longitudeFrom - $workShop->address_longitude;
                $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($workShop->address_latitude)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($workShop->address_latitude)) * cos(deg2rad($theta));
                $dist = acos($dist);
                $dist = rad2deg($dist);
                $miles = $dist * 60 * 1.1515;

                $distance  = ($miles * 1.609344).' km';

                               


                $workShopInfo = (object)[
                    "id" => $workShop->id,
                      'name'=> $workShop->name,
                      'phone'=> $workShop->phone,
                      'address'=> $workShop->address,
                      'workingTimeFrom'=> $workShop->workingTimeFrom,
                      'workingTimeTo'=> $workShop->workingTimeTo,
                      'available'=> $workShop->available,
                      'address_address'=> $workShop->address_address,
                      'distance'=>$distance,
                ];

                array_push($workShopDistance, $workShopInfo);
            }
            $workShop = collect($workShopDistance);
           return response()->json($workShop->sortBy('distance'),200);


        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }
    }
    public function sendRequest(Request $request,$workSopID){
        try {
            $userID=Auth::user()->id;
            $maintenanceRequest=MaintenanceRequest::create([
                'userID'=>$userID,
                'description'=>$request->input('description'),
                'confirm'=>0,
                'workshopID'=>$workSopID,
                'address_latitude'=>$request->input('address_latitude'),
                'address_longitude'=>$request->input('address_longitude'),
            ]);

           $workSop=WorkShop::find($workSopID);
           $user=User::find($workSop->workshopOwnerID);
       //     $user->notify(new SendMaintenanceRequest());

            return response()->json('Request send..',200);

        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }
    }

    public  function deleteRequest($requestID){
        try {
            $request=MaintenanceRequest::find($requestID);
            if(!$request){
                return response()->json('not found request..');
            }

            $request->delete();
            return response()->json('Request Deleted..');


        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }
    }

    public function myMaintenanceRequest(){

        try {
            $userID=Auth::user()->id;
            $MaintenanceRequest=MaintenanceRequest::where('userID',$userID)->get();
            if(count($MaintenanceRequest)==0){
                return response()->json('not found Maintenance Request for you ..',200);
            }
            return response()->json($MaintenanceRequest,200);
        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }
    }
}

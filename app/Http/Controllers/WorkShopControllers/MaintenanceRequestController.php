<?php

namespace App\Http\Controllers\WorkShopControllers;

use App\Http\Controllers\Controller;
use App\MaintenanceRequest;

use App\User;
use http\Exception;

use Illuminate\Support\Facades\Auth;

class MaintenanceRequestController extends Controller
{
     public function getAllMaintenanceRequest(){
         try {
               $workSop=Auth::user()->WorkShopOwner;
              $MaintenanceRequest=MaintenanceRequest::where('workshopID',$workSop->workshopOwnerID) ->get();
             if(!$MaintenanceRequest){
                 return response()->json('not found Maintenance Request',200);
             }
            return response()->json($MaintenanceRequest,200);

         }catch (Exception $exception){
             return response()->json("Error, Please make sure you are connected to the
            internet",500);
         }
     }

     public function confirmRequest($MaintenanceRequestID){
         try {
              $workShopOwner=Auth::user()->WorkShopOwner;
             $MaintenanceRequest=MaintenanceRequest::find($MaintenanceRequestID);
             if(!$MaintenanceRequest){
                 return response()->json('this request not available',200);
             }
             if($MaintenanceRequest->confirm!=0){
                 return response()->json('this request taken form another Workshop',200);
             }
             $MaintenanceRequest->update([
                 'confirm'=>1,
             ]);
             $user=User::find($MaintenanceRequest->userID);
          ##   $user->notify(new MaintenanceRequestNotification());

             return response()->json('Request Confirmed .. The workshop should go to the customer\'s site');

         }catch (Exception $exception){
             return response()->json("Error, Please make sure you are connected to the
            internet",500);
         }
     }

     public  function getMyConfirmedRequest(){
         try {
             $workShopOwner=Auth::user()->WorkShopOwner;
             $maintenanceRequest=MaintenanceRequest::where('workshopID',$workShopOwner->workshopOwnerID)
                 ->where('confirm',1)->get();
             if(count($maintenanceRequest)==0){
                 return response()->json('no Requests..',200);
             }

             return response()->json($maintenanceRequest,200);

         }catch (Exception $exception){
             return response()->json("Error, Please make sure you are connected to the
            internet",500);
         }
     }


     public function cancellationRequest($requestID){
         try {
             $request=MaintenanceRequest::find($requestID);
             if(!$request){
                 return response()->json('not found request');
             }
             $request->update([
                 'confirm'=>0
             ]);

             $user=User::find($request->userID);
           #  $user->notify(new MaintenanceRequestNotificationCancallation());

             return response()->json('Request Cancellation');



         }catch (Exception $exception){
             return response()->json("Error, Please make sure you are connected to the
            internet",500);
         }
     }
}

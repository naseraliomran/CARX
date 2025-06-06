<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Permission;
use App\User;
use App\WorkShop;
use http\Exception;
use Illuminate\Http\Request;

class WorkShopController extends Controller
{
    public function save(Request $requestWorkshop){
        try {
            $workshopOwner=User::create([
                'name'      =>$requestWorkshop->input('name'),
                'mobile'    =>$requestWorkshop->input('mobile'),
                'password'  =>app('hash')->make($requestWorkshop->input('password')),
                'gender'    =>$requestWorkshop->input('gender'),
                'age' =>$requestWorkshop->input('age'),
                'jop'       =>"Null"
            ]);
            $permission=Permission::create([
                'userID'          =>$workshopOwner->id,
                'permissionName'  =>"Workshop Owner",
            ]);

            $workshop=WorkShop::create(
                [
                    'name'           =>$requestWorkshop->input('nameWorkshop'),
                    'phone'          =>$requestWorkshop->input('phone'),
                    'address'        =>$requestWorkshop->input('address'),
                    'workingTimeFrom'=>$requestWorkshop->input('workingTimeFrom'),
                    'workingTimeTo'  =>$requestWorkshop->input('workingTimeTo'),
                    'address_longitude' =>$requestWorkshop->input('address_longitude'),
                    'address_latitude'  =>$requestWorkshop->input('address_latitude'),
                    'available'         =>0,// 0 => Available , 1 => unavailable
                    'workshopOwnerID'   =>$workshopOwner->id
                ]
            );
            return response()->json($workshop,200);
        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }
    }

    public function delete($workshopID){
        try {
           $workshop=WorkShop::find($workshopID);
           if(!$workshop){
               return response()->json('not found Workshop',404);
           }
           $workshopOwner=User::find($workshop->workshopOwnerID);
           $workshopOwner->delete();
           $workshop->delete();
           return response()->json('Workshop Deleted',200);
        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }
    }

    public function update(Request $requestWorkshop,$workshopID){
        try {
             $workshop=WorkShop::find($workshopID);
            if(!$workshop){
                return response()->json('not found Workshop',404);
            }
            $workshop->update([
                'name'           =>$requestWorkshop->input('nameWorkshop'),
                'phone'          =>$requestWorkshop->input('phone'),
                'address'        =>$requestWorkshop->input('address'),
                'workingTimeFrom'=>$requestWorkshop->input('workingTimeFrom'),
                'workingTimeTo'  =>$requestWorkshop->input('workingTimeTo'),
                'address_longitude' =>$requestWorkshop->input('address_longitude'),
                'address_latitude'  =>$requestWorkshop->input('address_latitude'),
            ]);
            return response()->json($workshop,200);

        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }
    }

    public function getAll(){
        try {
          $workshops=WorkShop::with('WorkshopOwner')->get();

            if(count($workshops)==0){
                return response()->json('not found Workshops',404);
            }

            return response()->json($workshops,200);

        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }
    }
}

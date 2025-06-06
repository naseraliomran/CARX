<?php

namespace App\Http\Controllers\CompanyDirectorControllers;

use App\Http\Controllers\Controller;
use App\Permission;
use App\Salesman;
use App\Store;
use App\User;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{

    public function saveStore(Request $storeRequest){
        try {
            $authUser=Auth::user();
            $company=User::find($authUser->id)->CompanyDirector;
            $store=Store::create([
                'storeName'=>$storeRequest->input('storeName'),
                'storeAddress'=>$storeRequest->input('storeAddress'),
                'companyID'=>$company->id,
                'phoneNumber'=>$storeRequest->input('phoneNumber'),
                'city'=>$storeRequest->input('city'),
                'freeze'=>0
            ]);
            return response()->json($store,200);
        }catch (Exception $exception){
            return response()->json("Error save Store, Please make sure you are connected to the
            internet",500);
        }
    }

    public function freezeStore($storeID){
        try {
            $store=Store::find($storeID);
            if(!$store){
                return response()->json('not found store',404);
            }
            $store->update([
                'freeze'=>1
            ]);
            return response()->json($store->storeName." Store has been frozen",200);
        }catch (Exception $exception){
            return response()->json("Error freeze Store, Please make sure you are connected to the
            internet",500);
        }

    }

    public function unfreezeStore($storeID){
        try {
            $store=Store::find($storeID);
            if(!$store){
                return response()->json('not found store',404);
            }
            $store->update([
                'freeze'=>0
            ]);
            return response()->json($store->storeName." Store has been frozen",200);
        }catch (Exception $exception){
            return response()->json("Error unfreeze Store, Please make sure you are connected to the
            internet",500);
        }

    }

    public function deleteStore($storeID){
        $store=Store::find($storeID);
        if(!$store){
            return response()->json('not found store',404);
        }
        $users=Salesman::join('users', 'users.id', '=', 'salesmen.userID')
            ->where([['salesmen.storeID', '=', $storeID]])
            ->get(['users.*']);

        if (count($users)!=0){

            foreach ($users as $user){
                Permission::where('userID',$user->id)->delete();
                Salesman::where('storeID',$storeID)->delete();
                User::find($user->id)->delete();

            }

        }

        $store->delete();
        return response()->json('store delete',200);
    }

    public function updateStore(Request $storeRequest,$storeID){
        $store=Store::find($storeID);
        if(!$store){
            return response()->json('not found store',404);
        }
        $store->update([
            'storeName'=>$storeRequest->input('storeName'),
            'storeAddress'=>$storeRequest->input('storeAddress'),
            'city'   =>$storeRequest->input('city'),
            'phoneNumber'=>$storeRequest->input('phoneNumber'),
        ]);
        return response()->json($store,'200');
    }

    public function getAllCompanyStores(){
        try {
            $authUser=Auth::user();
            $company=User::find($authUser->id)->CompanyDirector;

            $stores=Store::where('companyID',$company->id)->get();

            if(count($stores)==0){
                return response()->json('This Company does not have an Stores..',404);
            }
            return response()->json($stores,200);
        }catch (Exception $exception){
            return response()->json("Error Get All Company Stores, Please make sure you are connected to the
            internet",500);
        }

    }

    public function getInfoStore($storeID){
        $store=Store::find($storeID);
        if(!$store){
            return response()->json('Not found Store',404);
        }

        $user=Salesman::join('users', 'users.id', '=', 'salesmen.userID')
            ->where([['salesmen.storeID', '=', $storeID]])
            ->get(['users.*']);
        return $store.$user;
    }


}

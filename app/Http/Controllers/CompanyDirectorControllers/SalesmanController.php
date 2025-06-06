<?php

namespace App\Http\Controllers\CompanyDirectorControllers;

use App\Http\Controllers\Controller;
use App\Permission;
use App\Salesman;
use App\Store;
use App\User;
use http\Exception;
use Illuminate\Http\Request;

class SalesmanController extends Controller
{
     public function setSalesMan(Request $salesmanRequest,$storeID){
            try {
                $store=Store::find($storeID);
                if(!$store)
                {
                    return response()->json("Not found Store",404);

                }
                $salesmanUser=User::create([
                    'name'      =>$salesmanRequest->input('name'),
                    'mobile'    =>$salesmanRequest->input('mobile'),
                    'password'  =>app('hash')->make($salesmanRequest->input('password')),
                    'gender'    =>$salesmanRequest->input('gender'),
                    'age'    =>$salesmanRequest->input('age'),
                    'jop'    =>$salesmanRequest->input('jop')
                ]);
                $permission=Permission::create([
                    'userID'          =>$salesmanUser->id,
                    'permissionName'  =>"Salesman",
                ]);
                $salesman=Salesman::create([
                    'userID' =>$salesmanUser->id,
                    'storeID'=>$storeID
                ]);
                return response()->json($salesmanUser,200);
             }catch (Exception $exception){
                return response()->json("Error saving salesman, Please make sure you are connected to the
                internet",500);
            }
        }

        public function getSalesman($storeID){
            try {
                $store=Store::find($storeID);
                if(!$store){
                    return response()->json("not found store",404);
                }
                $salesman=Store::find($storeID)->Salesman;
                if(!$salesman){
                    return response()->json("there is no salesman in this store",404);
                }
                $user=Salesman::join('users', 'users.id', '=', 'salesmen.userID')
                    ->where([['salesmen.storeID', '=', $storeID]])
                    ->get(['users.*']);
                return response()->json( $user,200);

            }catch (Exception $exception){
                return response()->json("Error get Salesman, Please make sure you are connected to the
                internet",500);
            }
        }

        public function deleteSalesMan($salesmanID){
            $user=User::find($salesmanID);
            if(!$user){
                return response()->json('not found Salesman',404);
            }
            $per=User::find($salesmanID)->Permission;
            if($per->permissionName!='Salesman')
            {
                return 0;
            }
            $permission=Permission::where('userID',$user->id)->delete();
            $slaesman=Salesman::where('userID',$user->id)->delete();
            $user->delete();
            return response()->json('Salesman Deleted',200);

        }
}

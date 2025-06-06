<?php

namespace App\Http\Controllers\AdminControllers;

use App\CarsCompany;
use App\Http\Controllers\Controller;
use App\Permission;
use App\User;
use http\Exception;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function saveCompany(Request $companyRequest){
        try {
            $companyDirectorUser=User::create([
                'name'      =>$companyRequest->input('name'),
                'mobile'    =>$companyRequest->input('mobile'),
                'password'  =>app('hash')->make($companyRequest->input('password')),
                'gender'    =>$companyRequest->input('gender'),
                'age' =>$companyRequest->input('age'),
                'jop'       =>"Null"
            ]);
            $permission=Permission::create([
                'userID'          =>$companyDirectorUser->id,
                'permissionName'  =>"Company Director",
            ]);
            $carsCompany=CarsCompany::create([
                'nameCompany'  =>$companyRequest->input('nameCompany'),
                'code'     =>$companyRequest->input('code'),
                'companyDirectorID'=>$companyDirectorUser->id,
                'freeze'   =>0 // 0 => unfreeze  ,  1 => freeze
            ]);
            return response()->json($carsCompany."  ".$companyDirectorUser."   ".$permission,200);
        }catch (Exception $exception){

            return response()->json("Error saving Company, Please make sure you are connected to the
            internet",500);

        }

    }


    public function freezeCompany($companyID){

        $carsCompany=CarsCompany::find($companyID);
        if(!$carsCompany)
        {
            return response()->json("not found company",404);
        }

        $carsCompany->update([
            'freeze'=>1
        ]);
        return response()->json($carsCompany->name." Company has been frozen",200);
    }

    public function unfreezeCompany($companyID){

        $carsCompany=CarsCompany::find($companyID);
        if(!$carsCompany)
        {
            return response()->json("not found Company",404);
        }

        $carsCompany->update([
            'freeze'=>0
        ]);
        return response()->json($carsCompany->name." Company has been unfrozen",200);
    }



    public  function getAllCompanies(){
        $carsCompany=CarsCompany::with('CompanyDirector')->get();
        if(count($carsCompany)==0)
        {
            return response()->json('Not found Company',404);
        }
        return response()->json($carsCompany,200);
    }


    public function update(Request $companyRequest,$companyID)
    {
        try {
            $carsCompany=CarsCompany::find($companyID);
            if (!$carsCompany) {
                return response()->json("not found Company", 404);
            }
            $carsCompany->update([
                'nameCompany'  =>$companyRequest->input('nameCompany'),
                'code'     =>$companyRequest->input('code'),
            ]);
            return response()->json($carsCompany, 200);

        } catch (Exception $exception) {
            return response()->json("Error update Company, Please make sure you are connected to the
            internet",500);
        }
    }


    public function deleteCompanyDirector($companyDirectorID){
        try {
            $user=User::find($companyDirectorID);

            if(!$user){
                return response()->json('not found company Director ',404);
            }
            $per=User::find($companyDirectorID)->Permission;
            if($per->permissionName!='Company Director')
            {
                return 0;
            }
            $company=CarsCompany::where('companyDirectorID',$user->id)->get();

            $company=User::find($user->id)->CompanyDirector;
            $company->update([
                'companyDirectorID'=>0
            ]);
            $permission=Permission::where('userID',$user->id)->delete();
            $user->delete();
            return response()->json('This Brand does not have an agent..'.$company,404);

        }catch (Exception $exception){
            return response()->json("Error delete Brand Agent, Please make sure you are connected to the
            internet",500);
        }
    }


    public function getAllCompanyNotHaveCompanyDirector(){
        try {
            $carsCompany=CarsCompany::where('companyDirectorID',0)->get();
            if(count($carsCompany)==0){
                return response()->json('All Company have a Company Director',404);
            }
            return response()->json($carsCompany,200);

        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }

    }

    public function setCompanyDirector(Request $requestCompanyDirector,$companyID){
        try {
            $carsCompany=CarsCompany::find($companyID);
            if(!$carsCompany){
                return response()->json("Noy found Company",404);
            }
            if($carsCompany->companyDirectorID==0)
            {
                $companyDirectorUser=User::create([
                    'name'      =>$requestCompanyDirector->input('name'),
                    'mobile'    =>$requestCompanyDirector->input('mobile'),
                    'password'  =>app('hash')->make($requestCompanyDirector->input('password')),
                    'gender'    =>$requestCompanyDirector->input('gender'),
                    'age' =>$requestCompanyDirector->input('age'),
                    'jop'       =>"Null"
                ]);

                $permission=Permission::create([
                    'userID'          =>$companyDirectorUser->id,
                    'permissionName'  =>"Company Director",
                ]);


                $carsCompany->update([
                    'companyDirectorID'=>$companyDirectorUser->id
                ]);
                return response()->json($carsCompany.$companyDirectorUser.$permission ,200);

            }

            return response()->json('This Company has a Company Director',200);

        }catch (Exception $exception){
            return response()->json("Error set Brand Agent, Please make sure you are connected to the
            internet",500);
        }

    }

}

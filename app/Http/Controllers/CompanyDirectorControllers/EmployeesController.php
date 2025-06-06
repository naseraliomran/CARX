<?php

namespace App\Http\Controllers\CompanyDirectorControllers;

use App\CarsCompany;
use App\Employee;
use App\Http\Controllers\Controller;
use App\Permission;
use App\User;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeesController extends Controller
{
    public function save(Request $employeeRequest){
        try {
            $companyDirectorID=Auth::user()->id;
            $companyDirector=User::find($companyDirectorID);
            $companyID=$companyDirector->CompanyDirector->id;
            $employeeUser=User::create([
                'name'      =>$employeeRequest->input('name'),
                'mobile'    =>$employeeRequest->input('mobile'),
                'password'  =>app('hash')->make($employeeRequest->input('password')),
                'gender'    =>$employeeRequest->input('gender'),
                'age' =>$employeeRequest->input('age'),
                'jop'       =>$employeeRequest->input('jop')
            ]);
            $permission=Permission::create([
                'userID'          =>$employeeUser->id,
                'permissionName'  =>"Employee",
            ]);
           $employee=Employee::create([
               'userID'    => $employeeUser->id,
               'companyID' =>$companyID
           ]);

           return response()->json($employeeUser,200);
        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }

    }

    public function delete($employeeID){
        try {
            $employee=User::find($employeeID);
            if (!$employee){
                return response()->json('not found Employee',404);
            }
            $per=$employee->Permission;
            $emp=$employee->Employee;
            $companyID=$emp->companyID;
            $emp->delete();
            $per->delete();
            $employee->delete();
            $company=Employee::where('companyID',$companyID)->get();
            if(count($company)==0){
                return response()->json('Delete Employee and your company does not have employees',200);
            }
            return response()->json('deleted Employee',200);

        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }

    }

    public function update(Request $employeeRequest,$employeeID){
        try {
              $employeeUser=User::find($employeeID);
              if(!$employeeUser){
                  return  response()->json('not found employee',404);
              }
              $employeeUser->update([
                  'name'      =>$employeeRequest->input('name'),
                  'mobile'    =>$employeeRequest->input('mobile'),
                   'gender'    =>$employeeRequest->input('gender'),
                  'age' =>$employeeRequest->input('age'),
              ]);
              return response()->json($employeeUser,200);
        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }
    }

    public function getAllEmployee(){
        try {
           $companyDirectorID=Auth::user()->id;
           $companyDirector=User::find($companyDirectorID);
           $companyID=$companyDirector->CompanyDirector->id;
            $employees=Employee::with('User')->where('companyID',$companyID)->get();
            if(count($employees)==0){
                return response()->json('your company does not have employees',404);
            }
            return response()->json($employees,200);

        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }
    }
}

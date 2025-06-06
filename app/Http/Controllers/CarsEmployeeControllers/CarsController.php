<?php

namespace App\Http\Controllers\CarsEmployeeControllers;

use App\Car;
use App\Http\Controllers\Controller;
use App\img_cars;
use App\RentalCars;
use App\SoldCars;
use App\User;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarsController extends Controller
{
    public function saveCar(Request $requestCar){
        try {
            $user = Auth::user();
             $employee=User::find($user->id);
             $emp=$employee->Employee;
            $companyID=$emp->companyID;
            $car= Car::create([
                'idUser'=>$user->id,
                'companyID'=>$companyID,
                'state'=>$requestCar->state,  // 1 => for Selling  0 => for Rental
                'name'=>$requestCar->name,
                'describe'=>$requestCar->describe,
                'manufacturingYear'=>$requestCar->manufacturingYear,
                'price'=>$requestCar->price,
                'color'=>$requestCar->color,
                'city'=>$requestCar->city,
                'mileage'=>$requestCar->mileage,
                'engineCapacity'=>$requestCar->engineCapacity,
                'model'=>$requestCar->model,
                'carMode'=>0,
                'isBooking'=>0,

                'motionVector'=>$requestCar->motionVector
            ]);

            return response()->json($car,200);
        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }

    }

    public  function updateCar(Request $requestCar,$car_id)
    {
        try {
            $car= Car::find($car_id);
            if (!$car )
            {
                return response()->json('not found Car..',404);
            }
            $car->update([
                'name'=>$requestCar->name,
                'describe'=>$requestCar->describe,
                'manufacturingYear'=>$requestCar->manufacturingYear,
                'price'=>$requestCar->price,
                'color'=>$requestCar->color,
                'city'=>$requestCar->city,
                'mileage'=>$requestCar->mileage,
                'engineCapacity'=>$requestCar->engineCapacity,
                'model'=>$requestCar->model,
                'motionVector'=>$requestCar->motionVector
            ]);

            return response()->json($car,200);

        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }

    }

    public function getAllCars(){
        try {
            $user = Auth::user();
            $employee=User::find($user->id);
            $emp=$employee->Employee;
            $companyID=$emp->companyID;
            $cars=Car::where('companyID',$companyID)->with('imageCar')->get();
            if(count($cars)==0){

                return response()->json("you are dont have a cars",404);
            }
            return response()->json($cars,200);
        }catch (Exception $exception){
            return response()->json('you are dont have a cars','500');
        }

    }

    public function getAllCarsForSelling(){
        try {
            $user = Auth::user();
            $employee=User::find($user->id);
            $emp=$employee->Employee;
            $companyID=$emp->companyID;
            $cars=Car::where('companyID',$companyID)->where('state',1)->with('imageCar')->get();
            if(count($cars)==0){

                return response()->json("you are dont have a cars For Selling",404);
            }
            return response()->json($cars,200);
        }catch (Exception $exception){
            return response()->json('you are dont have a cars','500');
        }

    }

    public function getAllCarsForRental(){
        try {
            $user = Auth::user();
            $employee=User::find($user->id);
            $emp=$employee->Employee;
            $companyID=$emp->companyID;
            $cars=Car::where('companyID',$companyID)->where('state',0)->with('imageCar')->get();
            if(count($cars)==0){

                return response()->json("you are dont have a cars For Rental",404);
            }
            return response()->json($cars,200);
        }catch (Exception $exception){
            return response()->json('you are dont have a cars','500');
        }

    }


    public function deleteCar($car_id)
    {
        try {
            $car=Car::find($car_id);
            $imageCar=img_cars::where('idCar',$car_id);
            if (!$car )
            {
                return response()->json('not found car','404');
            }
            if (!$imageCar )
            {
                return response()->json('not found image car','404');
            }

            $car->delete();
            $imageCar->delete();

            return response()->json('car deleted','200');
        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }

    }


    public function uploadImageCar(Request $request,$car_id){
        try {
            $car=Car::find($car_id);
            if (!$car)
            {
                return response()->json('not found car',404);
            }
            $image=$request->file('imageName');
            $imagename=$image->getClientOriginalName();
            $image->move(public_path('/images/CarPictures'),$imagename);
            $imageCar=new img_cars();
            $imageCar->imageName=$imagename;
            $imageCar->idCar=$car_id;
            $imageCar->save();
            return response()->json('image saved',200);

        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }

       }


    public function deleteimage(Request $requestImageCar,$carID){
        try {
            $car=Car::find($carID);
            if (!$car)
            {
                return response()->json('not found car',404);
            }
            $fileName=$requestImageCar->get('imageName');
            img_cars::where('imageName',$fileName)->where('idCar',$carID)->delete();
            $path=public_path().'\images\CarPictures'.'\\'.$fileName;
            if(file_exists($path))
            {
                unlink($path);
                return response()->json('image deleted',200);
            }
            return response()->json('not found image',200);
        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }

    }

    public function saleCar(Request $request,$carID){
        try {
           $car=Car::find($carID);
           if(!$car){
               return response()->json('Not found Car',404);
           }
           if($car->carMode==1){
               return response()->json('This car is sold out',200);
           }
           $car->update([
              'carMode'=>1
           ]);
           $soldCar=SoldCars::create([
               'carID'      =>$car->id,
               'buyerName'  =>$request->input('buyerName'),
               'buyersPhoneNumber'=>$request->input('buyersPhoneNumber'),
           ]);
           $soldCar=SoldCars::find($soldCar->id)->with('Car')->get();
           return response()->json($soldCar,200);
        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }
    }

    public function carRental(Request $request,$carID){
        try {
            $car=Car::find($carID);
            if(!$car){
                return response()->json('Not found Car',404);
            }
            if($car->carMode==1){
                return response()->json('This car is for rent',200);
            }
            $car->update([
                'carMode'=>1
            ]);
            $carRental=RentalCars::create([
                'carID'      =>$car->id,
                'tenantName'  =>$request->input('tenantName'),
                'tenantPhoneNumber'=>$request->input('tenantPhoneNumber'),
                'bookingPeriod'=>$request->input('bookingPeriod'),
            ]);
            $carRental=RentalCars::find($carRental->id)->with('Car')->get();
            return response()->json($carRental,200);
        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }
    }

    public function getAllRentalCars(){
        try {
            $user = Auth::user();
            $employee=User::find($user->id);
            $emp=$employee->Employee;
            $companyID=$emp->companyID;
            $cars=Car::where('companyID',$companyID)->where('state',0)->where('carMode',1)->with('imageCar')->with('RentalCars')->get();
            if(count($cars)==0){
                return response()->json('There are no rental cars');
            }
            return response()->json($cars,200);
        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }

    }

    public function getAllSoldCars(){
        try {
            $user = Auth::user();
            $employee=User::find($user->id);
            $emp=$employee->Employee;
            $companyID=$emp->companyID;
            $cars=Car::where('companyID',$companyID)->where('state',1)->where('carMode',1)->with('imageCar')->with('SoldCars')->get();
            if(count($cars)==0){
                return response()->json('No cars sold');
            }
            return response()->json($cars,200);
        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }

        //
    }


    public function searchingForCar(Request $requestSearchingForCar){

        if($requestSearchingForCar->name!=null && $requestSearchingForCar->manufacturingYear!=null &&
            $requestSearchingForCar->pricefrom!=null &&$requestSearchingForCar->priceto!=null &&
            $requestSearchingForCar->city!=null)
        {
            $cars=Car::where('name','=',$requestSearchingForCar->name)
                ->where('manufacturingYear','=',$requestSearchingForCar->manufacturingYear)
                ->whereBetween('price',[$requestSearchingForCar->pricefrom,$requestSearchingForCar->priceto])
                ->where('city','=',$requestSearchingForCar->city)
                ->with('imageCar')
                ->get();
            if(count($cars)==0){
                return response()->json("not found Car",404);
            }

            return $cars;
        }
        if($requestSearchingForCar->name!=null && $requestSearchingForCar->manufacturingYear!=null &&
            $requestSearchingForCar->pricefrom!=null &&$requestSearchingForCar->priceto!=null &&
            $requestSearchingForCar->city==null)
        {
            $cars=Car::where('name','=',$requestSearchingForCar->name)
                ->where('manufacturingYear','=',$requestSearchingForCar->manufacturingYear)
                ->whereBetween('price',[$requestSearchingForCar->pricefrom,$requestSearchingForCar->priceto])
                ->with('imageCar')
                ->get();
            if(count($cars)==0){
                return response()->json("not found Car",404);
            }

            return $cars;
        }
        if($requestSearchingForCar->name!=null && $requestSearchingForCar->manufacturingYear!=null &&
            $requestSearchingForCar->pricefrom==null &&$requestSearchingForCar->priceto==null &&
            $requestSearchingForCar->city!=null)
        {
            $cars=Car::where('name','=',$requestSearchingForCar->name)
                ->where('manufacturingYear','=',$requestSearchingForCar->manufacturingYear)
                ->where('city','=',$requestSearchingForCar->city)
                ->with('imageCar')
                ->get();
            if(count($cars)==0){
                return response()->json("not found Car",404);
            }
            return $cars;
        }
        if($requestSearchingForCar->name!=null && $requestSearchingForCar->manufacturingYear==null &&
            $requestSearchingForCar->pricefrom!=null &&$requestSearchingForCar->priceto!=null &&
            $requestSearchingForCar->city!=null)
        {
            $cars=Car::where('name','=',$requestSearchingForCar->name)
                ->whereBetween('price',[$requestSearchingForCar->pricefrom,$requestSearchingForCar->priceto])
                ->where('city','=',$requestSearchingForCar->city)
                ->with('imageCar')
                ->get();
            if(count($cars)==0){
                return response()->json("not found Car",404);
            }
            return $cars;
        }
        if($requestSearchingForCar->name==null && $requestSearchingForCar->manufacturingYear!=null &&
            $requestSearchingForCar->pricefrom!=null &&$requestSearchingForCar->priceto!=null &&
            $requestSearchingForCar->city!=null)
        {
            $cars=Car::where('manufacturingYear','=',$requestSearchingForCar->manufacturingYear)
                ->whereBetween('price',[$requestSearchingForCar->pricefrom,$requestSearchingForCar->priceto])
                ->where('city','=',$requestSearchingForCar->city)
                ->with('imageCar')
                ->get();
            if(count($cars)==0){
                return response()->json("not found Car",404);
            }
            return $cars;
        }
        if($requestSearchingForCar->name==null && $requestSearchingForCar->manufacturingYear==null &&
            $requestSearchingForCar->pricefrom!=null &&$requestSearchingForCar->priceto!=null &&
            $requestSearchingForCar->city!=null)
        {
            $cars=Car::where('price','=',$requestSearchingForCar->price)
                ->where('city','=',$requestSearchingForCar->city)
                ->with('imageCar')
                ->get();
            if(count($cars)==0){
                return response()->json("not found Car",404);
            }
            return $cars;
        }
        if($requestSearchingForCar->name!=null && $requestSearchingForCar->manufacturingYear!=null &&
            $requestSearchingForCar->pricefrom==null &&$requestSearchingForCar->priceto==null &&
            $requestSearchingForCar->city==null)
        {
            $cars=Car::where('name','=',$requestSearchingForCar->name)
                ->where('manufacturingYear','=',$requestSearchingForCar->manufacturingYear)
                ->with('imageCar')
                ->get();
            if(count($cars)==0){
                return response()->json("not found Car",404);
            }
            return $cars;
        }
        if($requestSearchingForCar->name==null && $requestSearchingForCar->manufacturingYear!=null &&
            $requestSearchingForCar->pricefrom!=null &&$requestSearchingForCar->priceto!=null &&
            $requestSearchingForCar->city==null)
        {
            $cars=Car::where('price','=',$requestSearchingForCar->price)
                ->where('manufacturingYear','=',$requestSearchingForCar->manufacturingYear)
                ->with('imageCar')
                ->get();
            if(count($cars)==0){
                return response()->json("not found Car",404);
            }
            return $cars;
        }
        if($requestSearchingForCar->name!=null && $requestSearchingForCar->manufacturingYear==null &&
            $requestSearchingForCar->pricefrom==null &&$requestSearchingForCar->priceto==null &&
            $requestSearchingForCar->city!=null)
        {
            $cars=Car::where('name','=',$requestSearchingForCar->name)
                ->where('city','=',$requestSearchingForCar->city)
                ->with('imageCar')
                ->get();
            if(count($cars)==0){
                return response()->json("not found Car",404);
            }
            return $cars;
        }
        if($requestSearchingForCar->name==null && $requestSearchingForCar->manufacturingYear!=null &&
            $requestSearchingForCar->pricefrom==null &&$requestSearchingForCar->priceto==null &&
            $requestSearchingForCar->city!=null)
        {
            $cars=Car::where('manufacturingYear','=',$requestSearchingForCar->manufacturingYear)
                ->where('city','=',$requestSearchingForCar->city)
                ->with('imageCar')
                ->get();
            if(count($cars)==0){
                return response()->json("not found Car",404);
            }
            return $cars;
        }
        if($requestSearchingForCar->name!=null && $requestSearchingForCar->manufacturingYear==null &&
            $requestSearchingForCar->pricefrom!=null &&$requestSearchingForCar->priceto!=null &&
            $requestSearchingForCar->city==null)
        {
            $cars=Car::where('name','=',$requestSearchingForCar->name)
                ->whereBetween('price',[$requestSearchingForCar->pricefrom,$requestSearchingForCar->priceto])
                ->with('imageCar')
                ->get();
            if(count($cars)==0){
                return response()->json("not found Car",404);
            }
            return $cars;
        }
        if($requestSearchingForCar->name==null && $requestSearchingForCar->manufacturingYear==null &&
            $requestSearchingForCar->pricefrom==null &&$requestSearchingForCar->priceto==null &&
            $requestSearchingForCar->city!=null)
        {
            $cars=Car::where('city','=',$requestSearchingForCar->city)
                ->with('imageCar')
                ->get();
            if(count($cars)==0){
                return response()->json("not found Car",404);
            }
            return $cars;
        }
        if($requestSearchingForCar->name==null && $requestSearchingForCar->manufacturingYear==null &&
            $requestSearchingForCar->pricefrom!=null &&$requestSearchingForCar->priceto!=null &&
            $requestSearchingForCar->city==null)
        {
            $cars=Car::where('price','=',$requestSearchingForCar->price)
                ->with('imageCar')
                ->get();
            if(count($cars)==0){
                return response()->json("not found Car",404);
            }
            return $cars;
        }
        if($requestSearchingForCar->name==null && $requestSearchingForCar->manufacturingYear!=null &&
            $requestSearchingForCar->pricefrom==null &&$requestSearchingForCar->priceto==null &&
            $requestSearchingForCar->city==null)
        {
            $cars=Car::where('manufacturingYear','=',$requestSearchingForCar->manufacturingYear)
                ->with('imageCar')
                ->get();
            if(count($cars)==0){
                return response()->json("not found Car",404);
            }
            return $cars;
        }
        if($requestSearchingForCar->name!=null && $requestSearchingForCar->manufacturingYear==null &&
            $requestSearchingForCar->pricefrom==null &&$requestSearchingForCar->priceto==null &&
            $requestSearchingForCar->city==null)
        {
            $cars=Car::where('name','=',$requestSearchingForCar->name)
                ->with('imageCar')
                ->get();
            if(count($cars)==0){
                return response()->json("not found Car",404);
            }
            return $cars;
        }

        return response()->json("Please Select Value for searching","200");


    }

}

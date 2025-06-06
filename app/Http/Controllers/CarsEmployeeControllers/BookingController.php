<?php

namespace App\Http\Controllers\CarsEmployeeControllers;

use App\Bookings;
use App\Car;
use App\Http\Controllers\Controller;
use App\User;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function bookingCar($carID){
        try {
            $car=Car::find($carID);
            if(!$car){
                return response()->json('Not Found Car',404);
            }
            if($car->isBooking==1){
                return response()->json('This Car has been Booked',200);
            }
            $userID=Auth::user()->id;
            $employee=User::find($userID);
            $emp=$employee->Employee;
            $companyID=$emp->companyID;
            $booking=Bookings::create([
                'userID'=>$companyID,
                'carID'=>$carID,
                'confirm'=>0,

            ]);
            $car->update([
                'isBooking'=>1,
            ]);
            $carBooking=DB::table('bookings')
                ->join('cars','cars.id','=','bookings.carID')
//                ->join('users','users.id','=','bookings.userID')
                ->where('bookings.userID',$companyID)
//                ->where('bookings.carID',$car->id)
                ->get();
            return response()->json($carBooking,200);
        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }
    }

    public function cancellationOfBooking($bookingID){
        try {
            $bookingItem=Bookings::find($bookingID);
            if(!$bookingItem){
                return response()->json('Not Found Booking',200);

            }
            $car=Car::find($bookingItem->carID);
            $car->update([
                'isBooking'=>1,
            ]);
            $booking=Bookings::where('carID',$car->id)->get();
            foreach ($booking as $item){
                $item->delete();
            }
            return response()->json('Booking cancellation',200);
        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }

    }

    public function BookingsForMyCompany(){
        try {
            $userID=Auth::user()->id;
            $employee=User::find($userID);
            $emp=$employee->Employee;
            $companyID=$emp->companyID;

            $carBooking=DB::table('bookings')
                ->join('cars','cars.id','=','bookings.carID')
//                ->join('users','users.id','=','bookings.userID')
                ->where('bookings.userID',$companyID)
                ->get();
            if(count($carBooking)==0){
                return response()->json('Not found Cars booked');
            }

            return response()->json($carBooking,200);

        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }

    }

    public function rentalBookings(){
        try {
            $userID=Auth::user()->id;
            $employee=User::find($userID);
            $emp=$employee->Employee;
            $companyID=$emp->companyID;
            $carBooking=DB::table('bookings')
                ->join('cars','cars.id','=','bookings.carID')
//                ->join('users','users.id','=','bookings.userID')
                ->where('bookings.userID',$companyID)
                ->where('cars.state',0)
                ->get();
            if(count($carBooking)==0){
                return response()->json('Not found Cars booked');
            }

            return response()->json($carBooking,200);

        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }

    }

    public function soldBookings(){
        try {
            $userID=Auth::user()->id;
            $employee=User::find($userID);
            $emp=$employee->Employee;
            $companyID=$emp->companyID;
            $carBooking=DB::table('bookings')
                ->join('cars','cars.id','=','bookings.carID')
//                ->join('users','users.id','=','bookings.userID')
                ->where('bookings.userID',$companyID)
                ->where('cars.state',1)
                ->get();
            if(count($carBooking)==0){
                return response()->json('Not found Cars booked');
            }

            return response()->json($carBooking,200);

        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }

    }

    public function getRequestSoldBookings(){
        try {
            $userID=Auth::user()->id;
            $employee=User::find($userID);
            $emp=$employee->Employee;
            $companyID=$emp->companyID;
            $carBooking=DB::table('bookings')
                ->join('cars','cars.id','=','bookings.carID')
//                ->join('users','users.id','=','bookings.userID')
                ->where('cars.isBooking',1)
                ->where('cars.state',1)
                ->where('bookings.userID',$companyID)
                ->get();
            if(count($carBooking)==0){
                return response()->json('Not found Cars booked');
            }

            return response()->json($carBooking,200);

        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }

    }



    public function getRequestRentalBookings(){
        try {
            $userID=Auth::user()->id;
            $employee=User::find($userID);
            $emp=$employee->Employee;
            $companyID=$emp->companyID;
            $carBooking=DB::table('bookings')
                ->join('cars','cars.id','=','bookings.carID')
                ->join('users','users.id','=','bookings.userID')
                ->where('cars.isBooking',1)
                ->where('cars.state',0)
                ->where('bookings.userID',$companyID)
                ->get();
            if(count($carBooking)==0){
                return response()->json('Not found Cars booked');
            }

            return response()->json($carBooking,200);

        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }
    }

    public function confirmRequest($bookingID){
        try {

            $bookingItem= Bookings::find($bookingID);

            if(!$bookingItem){
                return response()->json('Not found Booking ..');
            }

            $bookingItem->update([
                'confirm'=>1,
            ]);
            return response()->json('confirm Booking',200);

        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }


    }


    public function rejectionBooking($bookingID){
        try {

            $bookingItem= Bookings::find($bookingID);

            if(!$bookingItem){
                return response()->json('Not found Booking ..');
            }

            $bookingItem->update([
                'confirm'=>2,
            ]);
            return response()->json('Rejection Booking',200);

        }catch (Exception $exception){
            return response()->json("Error, Please make sure you are connected to the
            internet",500);
        }


    }











}

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware([ 'api'])->group(function () {

    Route::post('register','RegisterController@create');
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('me', 'AuthController@me');

    // Company            ,'middleware'=>'Admin'
    Route::prefix('Company')->group(function (){
        Route::post('save', 'AdminControllers\CompanyController@saveCompany') ;
        Route::post('freeze/{companyID}', 'AdminControllers\CompanyController@freezeCompany') ;
        Route::post('unfreezeBrand/{companyID}', 'AdminControllers\CompanyController@unfreezeCompany') ;
        Route::get('getAll', 'AdminControllers\CompanyController@getAllCompanies') ;
        Route::post('update/{companyID}', 'AdminControllers\CompanyController@update') ;
        Route::get('AllCompanyNotHaveCompanyDirector', 'AdminControllers\CompanyController@getAllCompanyNotHaveCompanyDirector');
        Route::post('setCompanyDirector/{companyID}', 'AdminControllers\CompanyController@setCompanyDirector');
        Route::delete('deleteCompanyDirector/{companyDirectorID}', 'AdminControllers\CompanyController@deleteCompanyDirector');
    });


    Route::prefix('Workshop')->group(function (){
        Route::post('save', 'AdminControllers\WorkShopController@save') ;
        Route::get('getAll', 'AdminControllers\WorkShopController@getAll') ;
        Route::post('update/{workshopID}', 'AdminControllers\WorkShopController@update') ;
        Route::delete('delete/{workshopID}', 'AdminControllers\WorkShopController@delete');
    });

    Route::prefix('Employee')->group(function (){
        Route::post('save', 'CompanyDirectorControllers\EmployeesController@save') ;
        Route::get('getAll', 'CompanyDirectorControllers\EmployeesController@getAllEmployee') ;
        Route::post('update/{employeeID}', 'CompanyDirectorControllers\EmployeesController@update') ;
        Route::delete('delete/{employeeID}', 'CompanyDirectorControllers\EmployeesController@delete');
    });

    Route::prefix('Car')->group(function () {

        Route::post('save', 'CarsEmployeeControllers\CarsController@saveCar')->name('saveCar');
        Route::delete('delete/{id}', 'CarsEmployeeControllers\CarsController@deleteCar')->name('deleteCar');
        Route::post('update/{id}', 'CarsEmployeeControllers\CarsController@updateCar')->name('updateCar');
        Route::post('uploadImage/{id}', 'CarsEmployeeControllers\CarsController@uploadImageCar')->name('uploadImageCar');
        Route::post('deleteimage/{id}', 'CarsEmployeeControllers\CarsController@deleteimage')->name('deleteimage');
        Route::get('getAll', 'CarsEmployeeControllers\CarsController@getAllCars')->name('getAllCars');
        Route::get('getAllForSelling', 'CarsEmployeeControllers\CarsController@getAllCarsForSelling');
        Route::get('getAllForRental', 'CarsEmployeeControllers\CarsController@getAllCarsForRental');
        Route::get('getAllRentalCars', 'CarsEmployeeControllers\CarsController@getAllRentalCars');
        Route::get('getAllSoldCars', 'CarsEmployeeControllers\CarsController@getAllSoldCars');
        Route::post('saleCar/{carID}', 'CarsEmployeeControllers\CarsController@saleCar');
        Route::post('carRental/{carID}', 'CarsEmployeeControllers\CarsController@carRental');
        Route::post('search', 'CarsEmployeeControllers\CarsController@searchingForCar');

    });
//
    Route::prefix('Store')->group(function (){
        Route::post('save', 'CompanyDirectorControllers\StoreController@saveStore');
        Route::post('freeze/{storeID}', 'CompanyDirectorControllers\StoreController@freezeStore');
        Route::post('unfreeze/{storeID}', 'CompanyDirectorControllers\StoreController@unfreezeStore');
        Route::get('getAll', 'CompanyDirectorControllers\StoreController@getAllCompanyStores') ;
        Route::get('getInfoStore/{storeID}', 'CompanyDirectorControllers\StoreController@getInfoStore') ;
        Route::post('update/{storeID}', 'CompanyDirectorControllers\StoreController@updateStore');
        Route::delete('deleteStore/{storeID}', 'CompanyDirectorControllers\StoreController@deleteStore');
    });

    Route::prefix('Salesman')->group(function (){
        Route::post('setSalesman/{storeID}', 'CompanyDirectorControllers\SalesmanController@setSalesMan');
        Route::get('getSalesman/{storeID}', 'CompanyDirectorControllers\SalesmanController@getSalesman');
        Route::delete('deleteSalesMan/{salesmanID}', 'CompanyDirectorControllers\SalesmanController@deleteSalesMan');
    });

    Route::prefix('Parts')->group(function (){
        Route::post('save', 'SalesmanControllers\CarPartsController@savePart');
        Route::post('update/{partID}', 'SalesmanControllers\CarPartsController@updatePart');
        Route::delete('delete/{partID}', 'SalesmanControllers\CarPartsController@deletePart');
        Route::get('getAll', 'SalesmanControllers\CarPartsController@getPartInMyStore');
        Route::post('Sale/{partID}', 'SalesmanControllers\SalesController@Sale');

    });

    Route::prefix('User/Car')->group(function () {

        Route::post('save', 'UserControllers\CarController@saveCar')->name('saveCar');
        Route::delete('delete/{id}', 'UserControllers\CarController@deleteCar')->name('deleteCar');
        Route::post('update/{id}', 'UserControllers\CarController@updateCar')->name('updateCar');
        Route::post('uploadImage/{id}', 'UserControllers\CarController@uploadImageCar')->name('uploadImageCar');
        Route::post('deleteimage/{id}', 'UserControllers\CarController@deleteimage')->name('deleteimage');
        Route::get('getAll', 'UserControllers\CarController@getAllCars')->name('getAllCars');
        Route::get('getAllMyCars', 'UserControllers\CarController@getAllMyCars');
        Route::get('getAllForSelling', 'UserControllers\CarController@getAllCarsForSelling');
        Route::get('getAllForRental', 'UserControllers\CarController@getAllCarsForRental');
        Route::get('getAllRentalCars', 'UserControllers\CarController@getAllRentalCars');
        Route::get('getAllSoldCars', 'UserControllers\CarController@getAllSoldCars');
        Route::post('saleCar/{carID}', 'UserControllers\CarController@saleCar');
        Route::post('carRental/{carID}', 'UserControllers\CarController@carRental');
        Route::post('LoveCar/{carID}', 'UserControllers\CarController@LoveCar');
        Route::post('search', 'UserControllers\CarController@searchingForCar');

    });

    Route::prefix('User/Parts')->group(function (){
        Route::post('save', 'UserControllers\CarPartsController@savePart');
        Route::post('update/{partID}', 'UserControllers\CarPartsController@updatePart');
        Route::delete('delete/{partID}', 'UserControllers\CarPartsController@deletePart');
        Route::get('getAll', 'UserControllers\CarPartsController@getMyParts');
        Route::get('viewAllPartsCar', 'UserControllers\CarPartsController@viewAllPartsCar');
        Route::post('Sale/{partID}', 'UserControllers\SalesController@Sale');
        Route::post('search', 'UserControllers\CarPartsController@search');
        Route::post('filtering', 'UserControllers\CarPartsController@filtering');

    });


    Route::prefix('User/Booking')->group(function (){
        Route::post('bookingCar/{carID}', 'UserControllers\BookingController@bookingCar');
        Route::post('cancellationOfBooking/{bookingID}', 'UserControllers\BookingController@cancellationOfBooking');
        Route::get('myBookings', 'UserControllers\BookingController@myBookings');
        Route::get('rentalBookings', 'UserControllers\BookingController@rentalBookings');
        Route::get('soldBookings', 'UserControllers\BookingController@soldBookings');
        Route::get('getRequestSoldBookings', 'UserControllers\BookingController@getRequestSoldBookings');
        Route::get('getRequestRentalBookings', 'UserControllers\BookingController@getRequestRentalBookings');
        Route::get('confirmRequest/{bookingID}', 'UserControllers\BookingController@confirmRequest');
        Route::get('rejectionBooking/{bookingID}', 'UserControllers\BookingController@rejectionBooking');


    });

    Route::prefix('Employee/Booking')->group(function (){
        Route::post('bookingCar/{carID}', 'CarsEmployeeControllers\BookingController@bookingCar');
        Route::post('cancellationOfBooking/{bookingID}', 'CarsEmployeeControllers\BookingController@cancellationOfBooking');
        Route::get('myBookings', 'CarsEmployeeControllers\BookingController@BookingsForMyCompany');
        Route::get('rentalBookings', 'CarsEmployeeControllers\BookingController@rentalBookings');
        Route::get('soldBookings', 'CarsEmployeeControllers\BookingController@soldBookings');
        Route::get('getRequestSoldBookings', 'CarsEmployeeControllers\BookingController@getRequestSoldBookings');
        Route::get('getRequestRentalBookings', 'CarsEmployeeControllers\BookingController@getRequestRentalBookings');
        Route::get('confirmRequest/{bookingID}', 'CarsEmployeeControllers\BookingController@confirmRequest');
        Route::get('rejectionBooking/{bookingID}', 'CarsEmployeeControllers\BookingController@rejectionBooking');


    });

    Route::prefix('Customer/Order')->group(function (){
        Route::get('allMissingCarParts', 'UserControllers\CarPartsController@allMissingCarParts');
        Route::get('sendOrder/{carPartID}/{storeID}', 'UserControllers\CarPartsController@sendOrder');
        Route::get('myOrder', 'UserControllers\CarPartsController@myOrder');
        Route::delete('deleteOrder/{orderID}', 'UserControllers\CarPartsController@deleteOrder');

    });

    Route::prefix('Customer/Maintenance')->group(function (){
        Route::get('getWorkShops', 'UserControllers\MaintenanceRequestController@getWorkShops');
        Route::post('sendRequest/{workSopID}', 'UserControllers\MaintenanceRequestController@sendRequest');
        Route::post('rate', 'UserControllers\RatingController@rate');
        Route::delete('deleteRequest/{requestID}', 'UserControllers\MaintenanceRequestController@deleteRequest');
        Route::get('myMaintenanceRequest', 'UserControllers\MaintenanceRequestController@myMaintenanceRequest');

    });

    Route::prefix('WorkShop/Maintenance')->group(function (){
        Route::get('getAllMaintenanceRequest', 'WorkShopControllers\MaintenanceRequestController@getAllMaintenanceRequest');
        Route::get('confirmRequest/{MaintenanceRequestID}', 'WorkShopControllers\MaintenanceRequestController@confirmRequest');
        Route::put('cancellationRequest/{requestID}', 'WorkShopControllers\MaintenanceRequestController@cancellationRequest');
        Route::get('getMyConfirmedRequest', 'WorkShopControllers\MaintenanceRequestController@getMyConfirmedRequest');

    });
});

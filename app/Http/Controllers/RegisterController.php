<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Providers\RouteServiceProvider;
use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{


    /**
     * Where to redirect users after registration.
     *
     * @var string
     */


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'mobile'=>['required'],
            'jop'=>['required'],
            'age'=>['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return string
     */
    protected function create(\Illuminate\Http\Request $data)
    {

          $user=User::create([
            'name' => $data->name,
            'age' => $data->age,
            'mobile' => $data->mobile,
              'jop'=>$data->jop,
              'gender'=>$data->gender,
            'password' => Hash::make($data->password),
        ]);

          Permission::create([
              'userID'=>$user->id,
              'permissionName'=>"Customer",
          ]);

        return response()->json($user,200);
    }
}

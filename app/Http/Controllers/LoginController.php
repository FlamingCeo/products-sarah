<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;



class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails())
        {
            return response([
                'success' => false,
                'message' => "Valdation Error",
                'data'    =>$validator->errors()->all()
            ], 422);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $response = [
                    $user,
                    'success' => true,
                    'message' => 'Successfully Login',
                    'token' => $token
                ];
       
                return response($response, 200);
            } else {
                
                $response = ["message" => "Password mismatch",
                                'success' => false];
                return response($response, 422);
            }
        } else {
            $response = ["message" =>'User does not exist',
                          'success' => false  ];
            return response($response, 422);
        }
    }

    public function register (Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails())
        {

            return response([
                'success' => false,
                'message' => "Valdation Error",
                
                'data'    =>$validator->errors()->all()
            ], 422);
        }
        $request['password']=Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        $user = User::create($request->toArray());
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        return response([
            'success' => true,
            'message' => "New Account created",
            'token' => $token
        ], 200);
    }


    public function logout (Request $request) {
        $user =  Auth::user();
        //dd($user);
        Auth::user()->token()->revoke();
                  $response["data"] = [];
          $response["message"] = "Sucessfully Logged out";
          $response["status"] = true;


          return response($response, 200);
      
      }
}
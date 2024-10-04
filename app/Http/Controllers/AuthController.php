<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|min:5|confirmed',
            'phone' => 'required|digits:10',
            'device_name' => 'required',
            'profile' => 'required',
        ]);

        if ($validator->fails()) {
            $data = [
                'status' => 422,
                'message' => $validator->messages(),
            ];
            return response()->json($data, 400);
        }
        else{
            if($request->hasFile('profile')){
                $rand = rand();
                $ex = '.jpg';
                $filename = $rand.$ex;
                $path = $request->file('profile')->move(public_path('uploads/profiles/'),$filename);
                $profileUrl = url('/uploads/profiles/'.$filename);   
            }
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->phone = $request->phone;
            $user->device_name = $request->device_name;
            $user->profile = $profileUrl;
            $user->save();    
            $data = [
                'user' => $user,
                'message' => 'Registered successfully'
            ];
            return response()->json($data,200);
        }
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            $data = [
                'status' => 422,
                'message' => $validator->messages(),
            ];
            return response()->json($data, 400);
        }
        else{

        $user = User::where('email', $request->email)->first();

        if(!$user){
            return response([
                'message' => 'Not a valid user',
            ],200);
        }
        else if(!$user || !Hash::check($request->password,$user->password)){
            return response([
                'message' => 'Wrong Password try again',
            ],200);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;
        if($user){
        $user->login_token = $token;
        $user->save();
        }  
        $data = [
            'user' => $user,
            'token' => $token,
            'message' => 'Login Successfull'
        ];

        return response()->json($data,200);
    }
    }
    public function logout(Request $request){
        // $token = $request->bearerToken();
        // dd($token); // Debugging token
        if( $request->user()->currentAccessToken()->delete()) {
            return response([
                'message' => 'Logout Success'
            ], 200);
        } else {
            return response([
                'message' => 'Failed to logout'
            ], 500);
        }
    }
}

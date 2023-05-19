<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request){
        $validators = Validator::make($request->all(),[
            'name'=>'required|string|min:3|max:100',
            'email'=>'required|email',
            'password'=>'required|string|min:6',
        ]);

        if($validators->fails()){
            return response()->json($validators->errors(),400);
        }

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);

        return response()->json([
            'message'=>'User Registered!',
            'user'=>$user
        ]);
    }

    public function login(Request $request){
        $validators = Validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required|string'
        ]);

        if($validators->fails()){
            return response()->json($validators->errors(),400);
        }
        $token = auth()->attempt($validators->validated());
        if(!$token){
            return response()->json([
                'error'=>'Unauthorized!'
            ]);
        }
        return $this->responseWithToken($token);
    }

    protected function responseWithToken($token){
        return response()->json([
            'access_token'=>$token,
            'token_type'=>'bearer',
            'expires_in'=>auth()->factory()->getTTL()*60
        ]);
    }

    public function refreshToken(){
        return $this->responseWithToken(auth()->refresh());
    }

    public function logout(){
        auth()->logout();
        return response()->json(['message'=>'User Successfully Logged out!']);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request){
        $credentials=$request->only(['password']);
        $loginField = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';
        $credentials[$loginField]=$request->input('login');
        if(!$token=auth('api')->attempt($credentials)){
            return response()->json(['error'=>'Unauthorized'],401);
        }
        return response()->json(['token'=>$token]);

    }
    public function logout(){
        auth('api')->logout();
        return response()->json(['message'=>'Successfully logged out']);    
    }
}

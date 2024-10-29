<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Daneshamooz;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'first_name'=>'required|string|max:50',
            'last_name'=>'required|string|max:50',
            'phone_number'=>'required|digits:11|unique:daneshamooz',
            'email'=>'required|string|email|max:255|unique:daneshamooz',
            'password'=>'required|string|min:8|confirmed'
        ]);
        $user=Daneshamooz::create([
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'phone_number'=>$request->phone_number,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);
        //Auth::login($user);
        $token = Auth::guard('api')->attempt(['email' => $user->email, 'password' => $request->password]);        
        return response()->json([
            'access_token'=>$token,
            'token_type'=>'Bearer',
            'expires_in'=>auth()->factory()->getTTL()*60,
            'success'=>"register success!"
        ],201);
    }
    public function login(Request $request){
        $credentials=$request->only(['password']);
        $loginField=filter_var($request->input('login'),FILTER_VALIDATE_EMAIL)? 'email':'phone_number';
        $credentials[$loginField]=$request->input('login');
        if(!$token=Auth::attempt($credentials)){
            return response()->json(['error'=>'Unauthorized'],401);
        }
        return $this->respondWithToken($token);
        //return response()->json(['success'=>"Login success"],200);
    }
    public function logout(){
        Auth::guard('api')->logout();
        return response()->json(['message'=>'Successfully logged out']);
    }
    public function profile(){
        return response()->json(auth()->user());
    }
    protected function respondWithToken($token){
        
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

}

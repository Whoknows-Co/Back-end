<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Moshaver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MoshaverAuth extends Controller
{
    public function register(Request $request){
        try{
            $request->validate([
                'first_name'=>"required|string|max:50",
                "last_name"=>"required|string|max:50",
                "phone_number"=>"required|digits:11|unique:moshaver",
                "email"=>"required|string|email|max:255|unique:moshaver",
                "password"=>"required|string|min:8|confirmed"
            ]);
            $user=Moshaver::create([
                'first_name'=>$request->first_name,
                'last_name'=>$request->last_name,
                "phone_number"=>$request->phone_number,
                "email"=>$request->email,
                "password"=>Hash::make($request->password)
            ]);
            $token = Auth::guard('api')->attempt(['email' => $user->email, 'password' => $request->password]);        
            return response()->json([
                'access_token'=>$token,
                "token_type"=>"Bearer",
                "expires_in"=>auth()->factory()->getTTL()*60,
                'success'=>"register success!"
            ],201);
        }
        catch(\Illuminate\Validation\ValidationException $e){
            return response()->json(['error'=>$e->errors()],422);
        }
        catch(\Exception $e){
            return response()->json(['error'=>'Server error or duplicate entry'],500);
        }

    }
    public function login(Request $request){
        $credentials=$request->only(['password']);
        $loginField=filter_var($request->input('login'),FILTER_VALIDATE_EMAIL)? 'email':'phone_number';
        $credentials[$loginField]=$request->input('login');
        if(!$token=Auth::attempt($credentials)){
            return response()->json(['error'=>'Unauthorized'],401);  
        }
        return $this->respondWithToken($token);
    }
    public function logout(){
        Auth::guard('moshaver')->logout();
        return response()->json(['message'=>'Logged out successfully!']);
    }
    public function profile(){
        return response()->json(auth()->user());
    }
    protected function respondWithToken($token){
        return response()->json([
            'access_token'=>$token,
            'token_type'=>'Bearer',
            'expires_in'=>auth()->factory()->getTTL()*60
        ]);
    }
}

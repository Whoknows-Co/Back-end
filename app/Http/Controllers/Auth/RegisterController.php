<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Daneshamooz;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request){
        $validator=Validator::make($request->all(),[
            'first_name'=>'required|string|max:255',
            'last_name'=>'required|string|max:255',
            'phone_number'=>'required|digits:11|unique:Danesamooz',
            'email'=>'required|string|email|max:255|unique:Daneshamooz',
            'password'=>'required|string|confirmed|min:8'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }
        $user=Daneshamooz::create([
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'phone_number'=>$request->phone_number,
            'email'=>$request->email,
            'password'=>$request->Hash::make($request->password)
        ]);
        $token = auth('api')->login($user);
        return response()->json(['token'=>$token,'user'=>$user],201);
    }
    

}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Daneshamooz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    public function resetPassword(Request $request){
        $request->validate([
            'email' => 'required|email|exists:Daneshamooz,email',
            'password' => 'required|confirmed|min:8',
        ]);
        $user=Daneshamooz::where('email',$request->email)->first();
        if(!$user){
            return response()->json(['error'=>'User not found'],404);
        }
        $user->password=Hash::make($request->password);
        $user->save();
        return response()->json(['message'=>'Password successfully updated']);
    }
}

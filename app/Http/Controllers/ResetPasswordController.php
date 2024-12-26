<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
Use Illuminate\Validation\ValidationException;

class ResetPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request){
        $request->validate(['email'=>'required|email|exists:users,email']);//consider moshaver and daneshamooz
        $status=Password::sendResetLink(
            $request->only('email')
        );
        if($status===Password::RESET_LINK_SENT){
            return response()->json(['message'=>__($status)],200);
        }
        throw ValidationException::withMessages([
            'email'=>[trans($status)]
        ]);
    }
    public function resetPassword(Request $request){
        $request->validate([
            'token'=>'required',
            'email'=>'required|email|exits:users,email',
            'password'=>'required|min:8|confirmed',
        ]);
        $status=Password::reset(
            $request->only('email','password','password_confirmation','token'),
            function(User $user,string $password){
                $user->forceFill([
                    'password'=>Hash::make($password),
                    'remember_token'=>Str::random(60),
                ])->save();
            }
        );
        if($status===Password::PASSWORD_RESET){
            return response()->json(['message'=>__($status)],200);
        }
        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}

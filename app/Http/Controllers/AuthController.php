<?php

namespace App\Http\Controllers;

use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;
use App\Models\Daneshamooz;
use App\Models\Moshaver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request){
        $guard=$request->header('guard');
        if(!in_array($guard,['daneshamooz','moshaver'])){
            return response()->json(['error'=>"Invalid guard tyep"],400);
        }
        try{
            $request->validate([
                'first_name' => 'required|string|max:50',
                'last_name' => 'required|string|max:50',
                'phone_number' => 'required|digits:11|unique:' . $guard,
                'email' => 'required|string|email|max:255|unique:' . $guard,
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    function ($attribute, $value, $fail) {
                        if (!preg_match('/[A-Z]/', $value)) {
                            $fail('The password must contain at least one uppercase letter.');
                        }
                        if (!preg_match('/[a-z]/', $value)) {
                            $fail('The password must contain at least one lowercase letter.');
                        }
                        if (!preg_match('/[0-9]/', $value)) {
                            $fail('The password must contain at least one number.');
                        }
                    },
                ],
            ], [
                'password.min' => 'The password must be at least 8 characters long.',
                'password.confirmed' => 'The password confirmation does not match.',
            ]);
            if(!str_starts_with($request->phone_number,"09"))
                return response()->json(["The phone format is wrong"],422);
            $userModel=$guard==='daneshamooz'? new Daneshamooz() : new Moshaver();
            $user=$userModel::create([
                'first_name'=>$request->first_name,
                'last_name'=>$request->last_name,
                'phone_number'=>$request->phone_number,
                'email'=>$request->email,
                'password'=>Hash::make($request->password)
            ]);
            //Auth::login($user);
            //$token = Auth::guard($guard)->attempt(['email' => $user->email, 'password' => $request->password]);        
            return response()->json([
                'success'=>"register success!"
            ],201);}
            catch(\Exception $e){
                $error_type=$e->getMessage();
                if(str_starts_with($error_type,"The phone number has already been taken."))
                    return response()->json(['error'=>'The phone number has already been taken'],422);
                elseif(str_starts_with($error_type,"The email has already been taken."))
                    return response()->json(['error'=>"The email has already been taken"],422);
                elseif(str_starts_with($error_type,"The phone number field must be 11 digits"))
                    return response()->json(["error"=>"The phone number field must be 11 digits"],422);
                elseif(str_starts_with($error_type,"The password field must be at least 8 characters"))
                    return response()->json(["error"=>"The password field must be at least 8 characters"],422);
                elseif(str_starts_with($error_type,"The password field confirmation does not match"))
                    return response()->json(["error"=>'The password field confirmation does not match'],422);
                elseif(str_starts_with($error_type,"The email field must be a valid email address"))
                    return response()->json(["error"=>"The email field must be a valid email address"],422);
                elseif(str_starts_with($error_type,"The password must contain at least one uppercase letter"))
                    return response()->json(["error"=>"The password must contain at least one uppercase letter."],422);
                elseif(str_starts_with($error_type,"The password must contain at least one lowercase letter"))
                    return response()->json(["error"=>"The password must contain at least one lowercase letter."],422);
                
                return response()->json(['error'=>$e->getMessage()],500);
            }
    }
    public function login(Request $request){
        $guard=$request->header('guard');
        if(!in_array($guard,['daneshamooz','moshaver'])){
            return response()->json(['error'=>'Invalid guard type'],400);
        }
        $request->validate([
            'login'=>'required|string',
            'password'=>'required|string'
        ]);
        $credentials=$request->only(['password']);
        $loginField=filter_var($request->input('login'),FILTER_VALIDATE_EMAIL)? 'email':'phone_number';
        $credentials[$loginField]=$request->input('login');

        if(!$token=Auth::guard($guard)->attempt($credentials)){
            return response()->json(['error'=>'Unauthorized'],401);
        }
        $user=Auth::guard($guard)->user();
        if(($guard==='daneshamooz' && !$user instanceof Daneshamooz) || ($guard==='moshaver' && !$user instanceof Moshaver)){
            Auth::guard($guard)->logout();
            return response()->json(['error'=>'Invalid gurad for the provided credentials'],403);
        }
        return $this->respondWithToken($token);
        //return response()->json(['success'=>"Login success"],200);
    }
    public function logout(Request $request){
        $user=$request->user();
        $guard=$user instanceof Daneshamooz ? 'daneshamooz':'moshaver';
        Auth::guard($guard)->logout();
        return response()->json(['message'=>'Logged out successfully!'],200);

    }
    public function profile(){
        return response()->json(auth()->user());
    }
    protected function respondWithToken($token){
        
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }
    public function updateProfile(Request $request){
        try{
            $user=Auth::user();
            if(!$user){
                return response()->json(['error'=>'Unauthorized'],401);
            
            }
            $guard=$user instanceof Daneshamooz ? 'daneshamooz':'moshaver';
            $request->validate([
                'first_name' => 'nullable|string|max:50',
                'last_name' => 'nullable|string|max:50',
                'phone_number' => [
                    'nullable',
                    'digits:11',
                    'unique:' . $guard . ',phone_number,' . $user->id,
                    function ($attribute, $value, $fail) {
                        if ($value && !str_starts_with($value, '09')) {
                            $fail('Invalid phone format');
                        }
                    },
                ],
                'email' => 'nullable|string|email|max:255|unique:' . $guard . ',email,' . $user->id,
                'password' => 'nullable|string|min:8|confirmed',
            ], [
                'phone_number.digits' => 'The phone number must be exactly 11 digits.',
                'phone_number.unique' => 'The phone number is already in use.',
                'email.unique' => 'The email is already in use.',
                'password.min' => 'The password must be at least 8 characters long.',
                'password.confirmed' => 'The password confirmation does not match.',
            ]);

            $fieldsToUpdate=$request->only(['first_name','last_name','phone_number','email']);
            if($request->filled('password')){
                $fieldsToUpdate['password']=Hash::make($request->password);
            }
            $user->update($fieldsToUpdate);
            return response()->json(['message'=>'Profile updated successfully!'],200);

        }
        catch(\Exception $e){
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }

}
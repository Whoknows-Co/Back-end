<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MoshaverProfile;
use App\Models\Moshaver;
class MoshaverProfileController extends Controller
{
    public function update(Request $request){
        try{
            $validated = $request->validate([
                'specialty' => 'nullable|string',
                'city' => 'nullable|string',
                'address' => 'nullable|string',
                'about' => 'nullable|string',
                'services' => 'nullable|string',
                'social_media' => 'nullable|json',
                'display_phone' => 'nullable|string|digits:11']);
            $moshaver=Auth::user();
            if(!$moshaver){
                return response()->json(['error','Moshaver not authenticated'],401);
            }
            if(!str_starts_with($validated['display_phone'],"09")){
                return response()->json(["Error"=>"Invalid phone number format"],422);
            }
            
            MoshaverProfile::updateOrCreate(
                ['moshaver_id'=>$moshaver->id],
                array_merge($validated,['moshaver_id'=>$moshaver->id])
            );
            return response()->json(["message"=>"Information saved successfully!"],200);

        }
        catch(\Exception $e){
            
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }
    public function isComplete(Request $request){
        try{
            $moshaver=Auth::user();
            if(!$moshaver){
                return response()->json(['error','Moshaver not authenticated'],401);
            }
            $profile=$moshaver->moshaverProfile;
            if(!$profile){
                return response()->json(['message'=>false],200);
            }
            return response()->json(['message'=>$profile->is_complete],200);
            //return response()->json(['message'=>$profile->is_complete],200);
        }
        catch(\Exception $e){
            if(str_starts_with($e->getMessage(),"The display phone field must be 11 digits"))
                return response()->json(['error'=>"The display phone field must be 11 digits."],422);
            return response()->json(['error'=>"Error"],500);
        }
    }
    public function getCompletedMoshaverProfiles(Request $request)
    {
        try {
            $completedProfiles = MoshaverProfile::where('is_complete', true)
                ->with('moshaver')  
                ->get();
    
            if ($completedProfiles->isEmpty()) {
                return response()->json(['message' => 'No completed profiles found'], 404);
            }
    
            $profiles = $completedProfiles->map(function ($profile) {
                return [
                    'first_name' => $profile->moshaver->first_name,
                    'last_name' => $profile->moshaver->last_name,
                    'moshaver_id'=>$profile->moshaver->id,
                    'specialty' => $profile->specialty,
                    'city' => $profile->city,
                    'address' => $profile->address,
                    'about' => $profile->about,
                    'services' => $profile->services,
                    'social_media' => $profile->social_media,
                    'display_phone' => $profile->display_phone,   
                    'email' => $profile->moshaver->email,          
                ];
            });
    
            return response()->json($profiles, 200);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function showMoshaverProfile(Request $request)
    {
        try {
            $moshaver = auth()->user();
            if (!$moshaver) {
                return response()->json(['error' => 'Moshaver not authenticated'], 401);
            }
    
            $moshaverData = Moshaver::with('moshaverProfile')
                ->where('id', $moshaver->id)
                ->first();
    
            if (!$moshaverData) {
                return response()->json(['error' => "Moshaver not found"], 404);
            }
            $moshaverProfileData = $moshaverData->moshaverProfile 
                ? $moshaverData->moshaverProfile->only([
                    'specialty', 'city', 'address', 'about', 
                    'more_description', 'services', 'social_media', 
                    'display_phone'
                ])
                : null;
    
                $moshaverProfile = $moshaverData->moshaverProfile;

                $moshaverInfo = [
                    'first_name'       => $moshaverData->first_name,
                    'last_name'        => $moshaverData->last_name,
                    'email'            => $moshaverData->email,
                    'specialty'        => $moshaverProfile->specialty ?? null,
                    'city'             => $moshaverProfile->city ?? null,
                    'address'          => $moshaverProfile->address ?? null,
                    'about'            => $moshaverProfile->about ?? null,
                    'more_description' => $moshaverProfile->more_description ?? null,
                    'services'         => $moshaverProfile->services ?? null,
                    'social_media'     => $moshaverProfile->social_media ?? null,
                    'display_phone'    => $moshaverProfile->display_phone ?? null,
                ];
    
            return response()->json($moshaverInfo, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
}

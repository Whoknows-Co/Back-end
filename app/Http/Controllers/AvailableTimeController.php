<?php

namespace App\Http\Controllers;
use App\Models\AvailableTime;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;

class AvailableTimeController extends Controller
{
    public function store(Request $request){
        try{
            $validated=$request->validate([
                'moshaver_id'=>'required|exists:moshaver,id',
                'date'=>'required|date',
                'start_time'=>'required|date_format:H:i',
                'end_time'=>'required|date_format:H:i|after:start_time',
                'duration'=>'required|integer|min:15',
            ]);
            $overlap = AvailableTime::where('moshaver_id', $validated['moshaver_id'])
            ->where('date', $validated['date'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhere(function ($query) use ($validated) {
                        $query->where('start_time', '<=', $validated['start_time'])
                                ->where('end_time', '>=', $validated['end_time']);
                    });
            })
            ->exists();
            if($overlap){
                return response()->json([
                    'message'=>'The provided time range overlap with an existing time for this consultant'
                ],422);
            }
            
                
            
            $availableTime=AvailableTime::create($validated);
            return response()->json([
                'message'=>'Time saved successfully',
                'available_time'=>$availableTime,
            ],201);}
        catch(ValidationException $e){
            return response()->json([
                'message'=>'Validation failed'
            ],422);
        }
        catch(\Exception $e){
            return response()->json([
                'message'=>'Error'
            ],500);
        }
    }
    public function getSlots($moshaver_id,$date){
        $availableTime=AvailableTime::where('moshaver_id',$moshaver_id)
        ->where('date',$date)
        ->first(); //baressi
        if(!$availableTime){
            return response()->json(['message'=>"Time hasn't set for this consultant"],404); 
        }
        $start=strtotime($availableTime->start_time);
        $end=strtotime($availableTime->end_time);
        $duration=$availableTime->duration*60;
        $slots=[]; 
        while($start+$duration<=$end){
            $slots[]=date('H:i',$start);
            $start+=$duration;
        }
        return response()->json([
            'date'=>$availableTime->date,
            'slots'=>$slots
        ]);
    }
}

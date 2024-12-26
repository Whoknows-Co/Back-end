<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation2;
use App\Models\AvailableTime;
use App\Models\Slots;

class ReservationController2 extends Controller
{
    public function store(Request $request){
        try{
            $validated=$request->validate([
                'student_first_name'=>'required|string|max:255',
                'student_last_name'=>'required|string|max:255',
                'level'=>'required|string|max:255',
                'subject'=>'required|string|max:255',
                'date_birth'=>'required|date',
                'phone_number'=>'required|string|max:15',
                'moshaver_id'=>'required|exists:moshaver,id',
                'date'=>'required|exists:available_times,date',
                'time'=>'required|date_format:H:i'
            ]);
            $overlap=Reservation2::where('moshaver_id',$validated['moshaver_id'])
            ->where('date',$validated['date'])
            ->where('time',$validated['time'])
            ->exists();
            if($overlap){
                return response()->json([
                    'message'=>'This time slot is already reserved for the selected moshaver.'
                ],422);
            }
            $reservation=Reservation2::create($validated);
            $slot = Slots::where('moshaver_id', $validated['moshaver_id'])
            ->where('date', $validated['date'])
            ->where('time', $validated['time'])
            ->first();

            if ($slot) {
                $slot->status = 'unavailable';
                $slot->save();
            }
            return response()->json([
                'message'=>'Reservation created successfully.',
                'reservation'=>$reservation
            ],201);
        }
        catch(\Exception $e){
            return response([
                'message'=>$e->getMessage()
            ],500);
        }
    }
    public function getSlots($moshaver_id,$date){
        $availableTime=AvailableTime::where('moshaver_id',$moshaver_id)
        ->where('date',$date)
        ->first();
        if(!$availableTime){
            return response()->json(['message'=>'No available time for this moshaver on this date.'],404);
        }
        $start=strtotime($availableTime->start_time);
        $end=strtotime($availableTime->end_time);
        $duration=$availableTime->duration*60;
        $slots=[];
        while($start+$duration<=$end){
            $time=date('H:i',$start);
            $slot=Slots::firstOrCreate([
                'moshaver_id' => $moshaver_id,
                'date' => $date,
                'time' => $time,
            ],
            [
                'status' => 'available', 
            
            ]);
            $slots[] = [
                'time' => $slot->time,
                'status' => $slot->status,
            ];
            $start+=$duration;
        }
        return response()->json([
            'date' => $date,
            'slots' => $slots
        ]);
    }
}

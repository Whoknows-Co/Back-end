<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation2;
use App\Models\AvailableTime;
use App\Models\Slots;
use App\Models\Moshaver;
use Illuminate\Support\Facades\Auth;


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
                'date'=>'required|exists:slots,date',
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
            ->where('start_time', $validated['time'])
            ->first();

            if ($slot) {
                $slot->status = 'unavailable';
                $slot->save();
            }
            
            $reservation->makeHidden(['created_at', 'updated_at', 'id','moshaver_id']);
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
    public function getReservations($moshaver_id){
        try{
            $moshaver=Auth::user();
            if(!$moshaver){
                return response()->json(['error'=>'Unauthorized'],401);
            }
            $unavailableSlots=Slots::where('moshaver_id',$moshaver->id)
            ->where('status','unavailable')
            ->get(['date','time','status']);
            if($unavailableSlots->isEmpty()){
                return response()->json([
                    'message'=>"No reservation"
                ],200);
            }
            return response()->json([
                'moshaver_id'=>$moshaver->id,
                'reservations'=>$unavailableSlots
            ]);
        }
        catch(\Exception $e){
            return response()->json([
                'error'=>$e->getMessage()
            ],500);
        }
    }
    public function createSlots(Request $request)
    {
        try {
            $validated = $request->validate([
                'date' => 'required|date',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
                'duration' => 'required|integer|min:15',
            ]);
    
            $moshaverId = auth()->id(); 

    
            $startTime = strtotime($validated['start_time']);
            $endTime = strtotime($validated['end_time']);
            $duration = $validated['duration'] * 60; 
            $date = $validated['date'];
            $existingSlots = Slots::where('moshaver_id', $moshaverId)
            ->where('date', $date)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [date('H:i', $startTime), date('H:i', $endTime)])
                    ->orWhereBetween('end_time', [date('H:i', $startTime), date('H:i', $endTime)])
                    ->orWhere(function ($query) use ($startTime, $endTime) {
                        $query->where('start_time', '<=', date('H:i', $startTime))
                            ->where('end_time', '>=', date('H:i', $endTime));
                    });
            })
            ->exists();

            if ($existingSlots) {
                return response()->json([
                    'message' => 'A similar time range already exists for this consultant on the same day.',
                ], 422);
            }
            $slots = [];
            while ($startTime + $duration <= $endTime) {
                $slots[] = [
                    'moshaver_id' => $moshaverId,
                    'date' => $date,
                    'start_time' => date('H:i', $startTime),
                    'end_time'=>date('H:i',$startTime+$duration),
                    'status' => 'available',
                ];
                $startTime += $duration;
            }
            foreach($slots as $slot){
                Slots::create($slot);
            }
    
            return response()->json([
                'message' => 'Slots created successfully',
            ], 201);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
            ], 500);
        }
    }
    public function viewSlots(Request $request){
        try{
            $validated=$request->validate([
                'date'=>'required|date',
            ]);
            $moshaverId=auth()->id();
            $slots=Slots::where('moshaver_id',$moshaverId)
            ->where('date',$validated['date'])
            ->get(['id','start_time','end_time','status']);
            if($slots->isEmpty()){
                return response()->json([
                    'message' => 'No slots found for this date.',
                ], 404);
            }
            
            return response()->json([
                'slots' => $slots
            ],200);
        }
        catch(\Exception $e){
            return response()->json([
                'message'=>'An error occurred',
                'error'=>$e->getMessage()
            ],500);
        }
    }
    public function updateOrDeleteSlot(Request $request, $slot_id)
    {
        try {
            $moshaverId = auth()->id(); 
                        $slot = Slots::where('moshaver_id', $moshaverId)
                ->where('id', $slot_id)
                ->first();
    
            if (!$slot) {
                return response()->json([
                    'message' => 'Slot not found or you are not authorized to edit it.',
                ], 404);
            }
    
            if ($request->has('delete') && $request->delete === true) {
                $hasReservation = Reservation2::where('moshaver_id', $moshaverId)
                    ->where('date', $slot->date)
                    ->where('time', $slot->start_time)
                    ->exists();
    
                if ($hasReservation) {
                    return response()->json([
                        'message' => 'Cannot delete slot. There are reservations for this slot.',
                    ], 422);
                }
    
                $slot->delete();
    
                return response()->json([
                    'message' => 'Slot deleted successfully.',
                ], 200);
            }
    
            $validated = $request->validate([
                'status' => 'required|in:available,unavailable',
            ]);
    
            $slot->status = $validated['status'];
            $slot->save();
            $slot = $slot->makeHidden(['created_at', 'updated_at']);
            return response()->json([
                'message' => 'Slot updated successfully.',
                'slot' => $slot,
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    public function getReservedTimes(){
        try{
            $moshaver=Auth::user();
            if(!$moshaver){
                return response()->json([
                    "message"=>"Unauthorized access."
                ],403);
            }
            $reservations=Reservation2::where('moshaver_id',$moshaver->id)
            ->orderBy('date','asc')
            ->orderBy('time','asc')
            ->get(["id","student_first_name","student_last_name",'level','subject','date_birth','phone_number','date','time']);
            return response()->json([
                'reservation'=>$reservations
            ],200);
        }
        catch(\Exception $e){
            return response()->json([
                'message'=>$e->getMessage()
            ],500);
        }
    }
    public function getSlotsForDay($moshaver_id, $day)
{
    try {
        $moshaver = Moshaver::find($moshaver_id);
        if (!$moshaver) {
            return response()->json(['error' => 'Moshaver not found'], 404);
        }

        $slots = Slots::where('moshaver_id', $moshaver_id)
            ->where('date', $day)
            ->get(['start_time', 'end_time', 'status']); // می‌توانید فیلدهای دیگری اضافه کنید

        if ($slots->isEmpty()) {
            return response()->json(['message' => 'No slots available for this day'], 200);
        }

        return response()->json([

            'slots' => $slots,
        ], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


}

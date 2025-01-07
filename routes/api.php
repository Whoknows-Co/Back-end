<?php

use App\Http\Middleware\CheckMultipleGuards;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AvailableTimeController;
use App\Http\Controllers\ReservationController2;
use App\Http\Controllers\MoshaverAltController;
use App\Http\Controllers\MoshaverController;
use App\Http\Controllers\MoshaverProfileController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login'); 
Route::post('/logout', [AuthController::class, 'logout'])->middleware(CheckMultipleGuards::class);
Route::get('/profile', [AuthController::class, 'profile'])->middleware(CheckMultipleGuards::class);
//Route::post("/profile/update",[AuthController::class,"updateProfile"])->middleware(CheckMultipleGuards::class);
Route::get('/available-time/{moshaver_id}/{date}',[AvailableTimeController::class,'getSlots']);
//Route::post('/reservation',action: [ReservationController2::class,'store']);
Route::get('/moshavers', [MoshaverAltController::class, 'index']);
Route::get('/',[MoshaverController::class,'getLastFiveMoshavers']);
Route::middleware('auth:api')->get('/moshaver/profile', [MoshaverProfileController::class, 'show']);
Route::middleware('auth:api')->put('/moshaver/profile', [MoshaverProfileController::class, 'update']);
Route::post("/moshaver/complete-profile",[MoshaverProfileController::class,'update'])->middleware(CheckMultipleGuards::class);
Route::get("/moshaver/isComplete",[MoshaverProfileController::class,'isComplete'])->middleware(CheckMultipleGuards::class);
Route::get("/moshaver/profile",[MoshaverProfileController::class,"showMoshaverProfile"])->middleware(CheckMultipleGuards::class);
//Route::get('/moshaver/reservations',[ReservationController2::class,'getReservations'])->middleware(CheckMultipleGuards::class);
Route::post('/moshaver/submitTime',[ReservationController2::class,'createSlots'])->middleware(CheckMultipleGuards::class);
Route::post('moshaver/viewTimes',[ReservationController2::class,'viewSlots'])->middleware(CheckMultipleGuards::class);
Route::put('/moshaver/updateSlot/{slot_id}', [ReservationController2::class, 'updateSlot'])->middleware(CheckMultipleGuards::class);
Route::post('/reservation',action: [ReservationController2::class,'store']);
Route::get('/moshaver/reserved',action:[ReservationController2::class,'getReservedTimes'])->middleware(CheckMultipleGuards::class);
Route::get( "profilesNew",[MoshaverProfileController::class,'getCompletedMoshaverProfiles']);
Route::patch('/slots/{slot_id}',[ReservationController2::class,'updateOrDeleteSlot'])->middleware(CheckMultipleGuards::class);
Route::get('/reservation/slots/{moshaver_id}/{day}', [ReservationController2::class, 'getSlotsForDay']);

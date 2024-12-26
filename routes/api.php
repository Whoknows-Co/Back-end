<?php

use App\Http\Middleware\CheckMultipleGuards;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AvailableTimeController;
use App\Http\Controllers\ReservationController2;
use App\Http\Controllers\MoshaverAltController;
use App\Http\Controllers\MoshaverController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login'); 
Route::post('/logout', [AuthController::class, 'logout'])->middleware(CheckMultipleGuards::class);
Route::get('/profile', [AuthController::class, 'profile'])->middleware(CheckMultipleGuards::class);
Route::post('/available-time',[AvailableTimeController::class,'store']);
Route::get('/available-time/{moshaver_id}/{date}',[AvailableTimeController::class,'getSlots']);
Route::post('/reservation',[ReservationController2::class,'store']);
Route::get('/reservation/slots/{moshaver_id}/{date}',[ReservationController2::class,'getSlots']);
Route::get('/moshavers', [MoshaverAltController::class, 'index']);
Route::get('/',[MoshaverController::class,'getLastFiveMoshavers']);
Route::get("/test",function(){
    return response()->json(['one'=>'heello']);
});
Route::post('/password/email')
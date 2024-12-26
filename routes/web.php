<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckMultipleGuards;
use App\Http\Controllers\AvailableTimeController;
Route::get('/', function () {
    return view('welcome');
});
Route::get("/test",function(){
    dd("hello");
});

<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']); // Added 'login' method

// Route::middleware('auth:api')->group(function () {
//     Route::post('/logout', [AuthController::class, 'logout']); // Correct middleware usage
//     Route::get('/profile', [AuthController::class, 'profile']); // Correct middleware usage
// });
Route::post('/logout', [AuthController::class, 'logout']);
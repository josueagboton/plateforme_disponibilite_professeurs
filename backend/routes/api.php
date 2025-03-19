<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('/register', [AuthController::class, 'registerUser'])->name('register');
Route::post('/register/prof', [AuthController::class, 'registerProf'])->name('register.prof');
Route::post('/register/student', [AuthController::class, 'registerStudent'])->name('register.student');
Route::post('/register/administrator', [AuthController::class, 'registerAdministrator'])->name('register.administrator');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


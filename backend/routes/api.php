<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AvailabilityController;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('/register/student', [AuthController::class, 'registerUser'])->defaults('type', 'student');
Route::post('/register/professor', [AuthController::class, 'registerUser'])->defaults('type', 'professor');
Route::post('/register/administrator', [AuthController::class, 'registerUser'])->defaults('type', 'administrator');


Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::apiResource('courses', CourseController::class);
    Route::get('courses/trashed', [CourseController::class, 'trashed'])->name('courses.trashed');
    Route::patch('courses/{id}/restore', [CourseController::class, 'restore'])->name('courses.restore');;

    Route::apiResource('availability', AvailabilityController::class);
    Route::get('availability/trashed', [AvailabilityController::class, 'trashed'])->name('availability.trashed');
    Route::patch('availability/{id}/restore', [AvailabilityController::class, 'restore'])->name('availability.trashed');;

});


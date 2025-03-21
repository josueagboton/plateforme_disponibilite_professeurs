<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\AdministratorController;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('/register/student', [AuthController::class, 'registerUser'])->defaults('type', 'student');
Route::post('/register/professor', [AuthController::class, 'registerUser'])->defaults('type', 'professor');
Route::post('/register/administrator', [AuthController::class, 'registerUser'])->defaults('type', 'administrator');

//consulter l'emploie de temps par un etudiant
// Route::get('get/courses', CourseController::class, 'index');


Route::middleware('guest')->post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    //modifier le profil
    Route::put('/update-profil', [AuthController::class, 'updateProfil'])->name('updateProfile');


    Route::apiResource('courses', CourseController::class)->middleware('can:isAdmin');;
    Route::get('courses/trashed', [CourseController::class, 'trashed'])->name('courses.trashed');
    Route::patch('courses/{id}/restore', [CourseController::class, 'restore'])->name('courses.restore');;

    Route::apiResource('availability', AvailabilityController::class);
    Route::get('availability/trashed', [AvailabilityController::class, 'trashed'])->name('availability.trashed');
    Route::patch('availability/{id}/restore', [AvailabilityController::class, 'restore'])->name('availability.trashed');;


    //pour l'administrateur
    // Affecter un professeur à un cours
    Route::post('/courses/{id}/assign', [AdministratorController::class, 'assignToProfessor']);

});


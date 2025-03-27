<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\LevelEducationController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\WeeklyScheduleController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//enrégistrement d'un prof
Route::post('/register/professor', [AuthController::class, 'registerUser'])->defaults('type', 'professor');
//enregistrement d'un admin
Route::post('/register/administrator', [AuthController::class, 'registerUser'])->defaults('type', 'administrator');

//consulter l'emploie de temps par tout le monde sans authentification
Route::get('/weekly-schedule', [WeeklyScheduleController::class, 'getWeeklySchedule']);

//les filieres
Route::get('/departments', [DepartmentController::class, 'index']);

//recuperer les niveaux d'etudes
Route::get('/level-educations', [LevelEducationController::class, 'index']);

//connexion
Route::middleware('guest')->post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {

    //deconnexion
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    //modifier le profil
    Route::put('/update-profil', [AuthController::class, 'updateProfil'])->name('updateProfile');

    //module gestion des cours
    Route::apiResource('courses', CourseController::class);
    //liste des cours supprimés
    Route::get('courses/trashed', [CourseController::class, 'trashed'])->name('courses.trashed');
    //restorer un cours supprimé
    Route::patch('courses/{id}/restore', [CourseController::class, 'restore'])->name('courses.restore');;

    //module disponibilité
    Route::apiResource('availability', AvailabilityController::class);
    //availabilities trashed
    Route::get('availability/trashed', [AvailabilityController::class, 'trashed']);
    //availability restore
    Route::patch('availability/{id}/restore', [AvailabilityController::class, 'restore']);


    //pour l'administrateur
    // Affecter un professeur à un cours
    Route::post('/courses/{id}/assign', [AdministratorController::class, 'assignToProfessor']);

    //programmer un cours
    Route::post('/courses/{courseId}/schedule', [AdministratorController::class, 'scheduleCourse']);


    //pour les professeurs
    //la listes des profs
    Route::get('/professors', [ProfessorController::class, 'index']);

    //afficher un prof par son id
    Route::get('/professors/{id}', [ProfessorController::class, 'show']);


    //afficher les prof disponibles
    Route::get('available-teachers', [ProfessorController::class, 'availableTeachers']);

});


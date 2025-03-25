<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\Professors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfessorController extends Controller
{
    //liste des profs dispoble à partir d'une heurs

    public function availableTeachers()
    {
        try {
            $currentTime = now()->format('H:i:s');


            $startOfWeek = now()->startOfWeek()->format('Y-m-d');
            $endOfWeek = now()->endOfWeek()->format('Y-m-d');

            $profs = Professors::whereHas('availabilities', function ($query) use ($currentTime, $startOfWeek, $endOfWeek) {
                    $query->whereBetween('day', [$startOfWeek, $endOfWeek]); // Filtrer la semaine complète

                })->with('availabilities')->get();

            return response()->json([
                'professors' => $profs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des professeurs disponibles',
                'error' => $e->getMessage()
            ], 500);
        }
    }







}

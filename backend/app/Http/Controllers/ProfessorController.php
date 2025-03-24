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

            // $profs = Professors::where('availability', true)
            //     ->whereHas('availabilities', function ($query) use ($currentTime) {
            //         $query->where('day', today()->format('Y-m-d'))
            //             // ->where('hour_end', '>=', $currentTime);
            //     })->get();

            $startOfWeek = now()->startOfWeek()->format('Y-m-d');
            $endOfWeek = now()->endOfWeek()->format('Y-m-d');

            $profs = Professors::where('availability', true)
                ->whereHas('availabilities', function ($query) use ($currentTime, $startOfWeek, $endOfWeek) {
                    $query->whereBetween('day', [$startOfWeek, $endOfWeek]) // Filtrer la semaine complète
                        ->where('hour_start', '<=', $currentTime) // Vérifier la plage horaire
                        ->where('hour_end', '>=', $currentTime);
                })->get();

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

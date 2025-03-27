<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\Professors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\throwException;

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

    public function index()
    {
        $professors = Professors::where('role', 'professor')->get();

        return response()->json([
            'status' => 'successfully ',
            'data' => $professors
        ]);
    }

    public function show($id)
    {
        $professor = Professors::find($id);

        if (!$professor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Professor not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $professor
        ]);
    }








}

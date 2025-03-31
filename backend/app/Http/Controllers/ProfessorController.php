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

            $profs = Professors::where('role', 'professor')
            ->whereHas('availabilities') // Vérifie que la relation 'availabilities' existe (count > 0)
            ->with('availabilities')
            ->get();

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

    //liste des profs inscrits
    public function index()
    {
        $professors = Professors::where('role', 'professor')->get();

        return response()->json([
            'status' => 'successfully ',
            'data' => $professors
        ]);
    }

    //afficher les details d'un profs et ses disponibilites
    public function show($id)
    {
        $professor = Professors::with('availabilities')->find($id);

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

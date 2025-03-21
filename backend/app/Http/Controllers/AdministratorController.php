<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use App\Models\Professors;
use App\Models\User;
use Illuminate\Http\Request;

class AdministratorController extends Controller
{
    //assigner un cours a un prof
    public function assignToProfessor(Request $request, $courseId)
    {
        $validated = $request->validate([
            'professor_id' => 'required|exists:professors,id'
        ]);

        $course = Courses::findOrFail($courseId);
        $professor = Professors::where('id', $validated['professor_id'])->where('role', 'professor')->first();

        if (!$professor) {
            return response()->json([
                'message' => 'Professeur non trouvé ou rôle incorrect'
            ], 404);
        }

        $course->update(['professor_id' => $validated['professor_id']]);

        return response()->json([
            'message' => 'Cours affecté avec succès au professeur',
            'course' => $course->load('professor')
        ]);
    }

     //  Lister tous les cours avec les professeurs assignés
    public function index()
    {
        $courses = Courses::with('professor')->get();

        return response()->json($courses);
    }

     // Afficher un cours spécifique avec le professeur assigné
    public function show($id)
    {
        $course = Courses::with('professor')->findOrFail($id);

        return response()->json($course);
    }

    //creation de l'emploi de temps de la semaine

    //les prof ont donne leurs disp

    //l'adimin en fonction de la disponibilite a programmer le prof

    //cela suppose que plusieurs enseignants ont ete programmer

    //le systeme va generer l'emploi de temps de la semaine
}

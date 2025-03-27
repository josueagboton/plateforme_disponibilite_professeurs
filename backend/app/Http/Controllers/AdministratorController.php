<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use App\Models\CourseSchedule;
use App\Models\Professors;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdministratorController extends Controller
{
    //assigner un cours a un prof
    public function assignToProfessor(Request $request, $courseId)
    {
        $validated = $request->validate([
            'professor_id' => 'required|exists:professors,id'
        ]);

        $course = Courses::findOrFail($courseId);
        $professor = Professors::where('id', $validated['professor_id'])->first();

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

    public function scheduleCourse(Request $request, $courseId)
    {
        try {
        $request->validate([
            'day' => 'required|date|after_or_equal:today', // Le jour doit être valide et >= aujourd'hui
            'hour_start' => 'required|date_format:H:i',
            'hour_end' => 'required|date_format:H:i|after:hour_start',
            'user_id' => 'required|exists:users,id',
            'level_education_id' => 'required|exists:level_education,id',
            'department_id'  => 'required|exists:departments,id',
        ]);

        $course = Courses::find($courseId);

        if (!$course) {
            return response()->json([
                'message' => 'Cours introuvable.'
            ], 404);
        }

        $professor = Professors::find($request->user_id);

        if(!$professor){
            return response()->json([
                'error' => "User is not Professor",
            ], 422);
        }


        //  formatage de la date
        $day = Carbon::parse($request->day);

        //  Vérification si le professeur est disponible à cette heure et ce jour
        $isAvailable = $professor->availabilities()
            ->whereDate('day', $day)
            ->whereTime('hour_start', '<=', $request->hour_start)
            ->whereTime('hour_end', '>=', $request->hour_end)
            ->exists();

        if (!$isAvailable) {
            return response()->json([
                'message' => 'Le professeur n\'est pas disponible à cette heure.'
            ], 400);
        }
        //  Vérification de conflit avec un autre cours déjà programmé
        $isConflict = CourseSchedule::where('user_id', $professor->id)
            ->where('day', $day)
            ->where(function ($query) use ($request) {
                $query->whereBetween('hour_start', [$request->hour_start, $request->hour_end])
                    ->orWhereBetween('hour_end', [$request->hour_start, $request->hour_end])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('hour_start', '<', $request->hour_start)
                            ->where('hour_end', '>', $request->hour_end);
                    });
            })
            ->exists();

        if ($isConflict) {
            return response()->json([
                'message' => 'Un autre cours est déjà programmé pour ce professeur à ce créneau.'
            ], 400);
        }

        //  Enregistrer la programmation du cours
        $schedule = CourseSchedule::create([
            'day' => $request->day,
            'hour_start' => $request->hour_start,
            'hour_end' => $request->hour_end,
            'user_id' => $professor->id,
            'course_id' => $course->id,
            'department_id' => $request->department_id,
            'level_education_id' => $request->level_education_id
        ]);

        return response()->json([
            'message' => 'Cours programmé avec succès!',
            'schedule' => $schedule
        ]);
    }
    catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'message' => 'Erreur de validation',
            'errors' => $e->errors()
        ], 422);
    }

    }





}

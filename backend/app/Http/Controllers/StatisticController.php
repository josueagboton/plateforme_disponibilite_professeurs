<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use App\Models\Professors;
use App\Models\Availability;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function getStatistics()
    {
        // Nombre total de cours programmés
        $totalCourses = Courses::count();

        // Nombre de professeurs disponibles
        $totalProfessors = Professors::count();

        // Taux d'occupation horaire (nombre total d'heures de cours)
        $totalHours = Courses::sum('duration');

        // Taux d'occupation par professeur
        $professorOccupation = ProfessorS::with('courses')->get()->map(function ($professor) {
            $totalHours = $professor->courses->sum('duration');
            $availableHours = Availability::where('user_id', $professor->id)
                ->sumRaw('TIMESTAMPDIFF(HOUR, hour_Start, hour_End)');

            $occupationRate = $availableHours > 0 ? ($totalHours / $availableHours) * 100 : 0;

            return [
                'professor' => $professor->name,
                'total_hours' => $totalHours,
                'available_hours' => $availableHours,
                'occupation_rate' => round($occupationRate, 2),
            ];
        });

        // Taux d'absence = (Nombre de créneaux disponibles non occupés)
        $absences = $professorOccupation->map(function ($professor) {
            $unoccupiedHours = $professor['available_hours'] - $professor['total_hours'];
            $absenceRate = $professor['available_hours'] > 0
                ? ($unoccupiedHours / $professor['available_hours']) * 100
                : 0;

            return [
                'professor' => $professor['professor'],
                'unoccupied_hours' => $unoccupiedHours,
                'absence_rate' => round($absenceRate, 2),
            ];
        });

        return response()->json([
            'total_courses' => $totalCourses,
            'total_hours' => $totalHours,
            'professor_occupation' => $professorOccupation,
            'absences' => $absences,
        ]);
    }

}

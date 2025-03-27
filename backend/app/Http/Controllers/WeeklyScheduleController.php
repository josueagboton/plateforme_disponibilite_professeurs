<?php

namespace App\Http\Controllers;

use App\Models\CourseSchedule;
use Illuminate\Http\Request;

class WeeklyScheduleController extends Controller
{
    public function getWeeklySchedule()
    {
        $startOfWeek = now()->startOfWeek(); // Lundi à 00:00:00
        $endOfWeek = now()->endOfWeek(); // Dimanche à 23:59:59

        $schedules = CourseSchedule::with(['course', 'professor'])
            ->whereBetween('day', [$startOfWeek, $endOfWeek])
            ->orderBy('day')
            ->orderBy('hour_start')
            ->get();

        return response()->json($schedules);
    }
}

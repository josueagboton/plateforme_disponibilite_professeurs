<?php

namespace App\Http\Controllers;

use App\Models\CourseSchedule;
use Illuminate\Http\Request;

class WeeklyScheduleController extends Controller
{
    public function getWeeklySchedule()
    {

        $schedules = CourseSchedule::with(['course', 'professor'])
            ->orderBy('day')
            ->orderBy('hour_start')
            ->get();

        return response()->json($schedules);
    }
}

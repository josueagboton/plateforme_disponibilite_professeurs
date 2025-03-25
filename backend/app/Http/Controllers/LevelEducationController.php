<?php

namespace App\Http\Controllers;

use App\Models\LevelEducation;
use Illuminate\Http\Request;

class LevelEducationController extends Controller
{
    public function index()
    {
        $levels = LevelEducation::with('departments')->get();

        return response()->json([
            'success' => true,
            'data' => $levels
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('levels')->get();

        return response()->json([
            'success' => true,
            'data' => $departments
        ]);
    }
}

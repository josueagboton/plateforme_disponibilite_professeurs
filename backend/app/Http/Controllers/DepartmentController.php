<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    //afficher la liste des filières
    public function index()
    {
        $departments = Department::with('levels')->get();

        return response()->json([
            'success' => "success",
            'data' => $departments
        ]);
    }
}

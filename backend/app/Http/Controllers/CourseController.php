<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use App\Models\Professors;
use App\Models\User;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Courses::all();
        return response()->json($courses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            //code...
            $request->validate([
                'subject_taught' => 'required|unique:courses|string|max:255',
                'duration' => 'required|integer',
                'description' => 'required|string',
            ]);

            $course = Courses::create([
                'subject_taught' => $request->subject_taught,
                'duration' => $request->duration,
                'description' => $request->description,
            ]);

            return response()->json($course, 201);
         } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $course = Courses::with('professor')->find($id);
        $course = Courses::find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        return response()->json($course);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       try {
        //code...
        $course = Courses::find($id);
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $validated = $request->validate([
            'subject_taught' => 'sometimes|string|max:255',
            'duration' => 'integer',
            'description' => 'sometimes|string',
            // 'user_id' => 'sometimes|exists:users,id',
        ]);

        $course->update($validated);

        return response()->json($course);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $course = Courses::find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $course->delete();

        return response()->json(['message' => 'Course deleted successfully']);

    }

    // Restaurer un cours supprimé
    public function restore($id)
    {
        $course = Courses::withTrashed()->findOrFail($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $course->restore();

        return response()->json(['message' => 'Course restored successfully']);
    }

    //corbeil
    public function trashed()
    {
        $courses = Courses::onlyTrashed()->get();
        return response()->json($courses);
    }
}

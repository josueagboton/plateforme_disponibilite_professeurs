<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Courses::with('professor')->get();
        return response()->json($courses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_taught' => 'required|string|max:255',
            'duration' => 'required|integer',
            'description' => 'required|string',
            'professor_id' => 'required|exists:professors,id',
        ]);

        $course = Courses::create($validated);

        return response()->json($course, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $course = Courses::with('professor')->find($id);
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
        $course = Courses::find($id);
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $validated = $request->validate([
            'subject_taught' => 'sometimes|string|max:255',
            'duration' => 'sometimes|date_format:H:i:s',
            'description' => 'sometimes|string',
            'professor_id' => 'sometimes|exists:professors,id',
        ]);

        $course->update($validated);

        return response()->json($course);
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

    // ✅ Restaurer un cours supprimé
    public function restored($id)
    {
        $course = Courses::withTrashed()->findOrFail($id);
        $course->restore(); // Restore le cours
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

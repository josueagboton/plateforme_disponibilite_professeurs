<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $availabilities = Availability::with('professor')->get();
        return response()->json($availabilities);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'day' => 'required|date',
            'hour_Start' => 'required|date_format:H:i',
            'hour_End' => 'required|date_format:H:i|after:hour_Start',
            'professor_id' => 'required|exists:professors,id',
        ]);

        $availability = Availability::create($request->all());

        return response()->json($availability, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $availability = Availability::findOrFail($id);
        if (!$availability) {
            return response()->json(['message' => 'Availability not found'], 404);
        }
        return response()->json($availability);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $availability = Availability::findOrFail($id);

        $request->validate([
            'day' => 'required|date',
            'hour_Start' => 'required|date_format:H:i',
            'hour_End' => 'required|date_format:H:i|after:hour_Start',
            'professor_id' => 'required|exists:professors,id',
        ]);

        $availability->update($request->all());

        return response()->json($availability);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $availability = Availability::findOrFail($id);
        $availability->delete();

        return response()->json(['message' => 'Availability deleted successfully']);
    }
    // ✅ Restaurer une disponibilité supprimée
    public function restore($id)
    {
        $availability = Availability::withTrashed()->findOrFail($id);
        $availability->restore();

        return response()->json(['message' => 'Availability restored successfully']);
    }

    // ✅ Liste des disponibilités supprimées
    public function trashed()
    {
        $availabilities = Availability::onlyTrashed()->get();

        return response()->json($availabilities);
    }
}

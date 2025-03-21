<?php

namespace App\Http\Controllers;

use App\Models\Professors;
use App\Models\Availability;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        try {
             // Validation des données
        $request->validate([
            'day' => 'required|date',
            'hour_Start' => 'required|date_format:H:i',
            'hour_End' => 'required|date_format:H:i|after:hour_Start',
            'professor_id' => 'required|exists:professors,id',
        ]);

        // Vérifiez si le professeur existe
        $professorExists = User::find($request->professor_id);
        if (!$professorExists) {
            return response()->json(['error' => 'Professeur introuvable'], 400);
        }


        // Création de la disponibilité
        $availability = Availability::create([
            'day' => $request->day,
            'hour_Start' => $request->hour_Start,
            'hour_End' => $request->hour_End,
            'professor_id' => $request->professor_id,
        ]);

        // Retourner la disponibilité créée en réponse
        return response()->json(['availability' => $availability,
                            "success"=> "Success"], 201);
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
        $availability = Availability::with('professor')->find($id);

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

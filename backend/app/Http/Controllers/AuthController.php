<?php

namespace App\Http\Controllers;

use App\Models\Administrators;
use App\Models\Professors;
use App\Models\Students;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function registerUser(Request $request, string $type)
    {
        try {
            // Validation commune
            $baseValidation = [
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'sex' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
            ];

            $extraValidation = match ($type) {
                'professor' => [
                    'grade' => 'required|string|max:255'
                ],
                'student' => [
                    'level_of_education' => 'required|string|max:255',
                ],
                'administrator' => [
                    'function' => 'required|string|max:255',
                ],
                default => [],
            };

            $validated = $request->validate(array_merge($baseValidation, $extraValidation));

            // Création de l'utilisateur
            $user = User::create([
                'firstname' => $validated['firstname'],
                'lastname' => $validated['lastname'],
                'sex' => $validated['sex'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $type
            ]);

            // Association selon le type d'utilisateur
            $data = match ($type) {
                'professor' => Professors::create([
                    'grade' => $validated['grade'],
                    'user_id' => $user->id,
                    'role' => "Professor"

                ]),
                'student' => Students::create([
                    'level_of_education' => $validated['level_of_education'],
                    'user_id' => $user->id,
                    'role' => "Student"

                ]),
                'administrator' => Administrators::create([
                    'function' => $validated['function'],
                    'user_id' => $user->id,
                    'role' => "Administrator"

                ]),
                default => null,
            };

            // Génération du token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'data' => $data,
                'token' => $token,
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        }
    }

   public function login(Request $request)
    {
        // Validation des champs requis
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Si l'authentification échoue, retourne une réponse 401
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();

        //conpletter information si prof

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie'
        ]);
    }



}

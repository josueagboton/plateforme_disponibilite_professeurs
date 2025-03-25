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
            $validated = $this->validateUserData($request, $type);
            $data = $this->createUser($type, $validated);

            return response()->json([
                'data' => $data,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        }
    }

    protected function validateUserData(Request $request, string $type)
    {
        $baseValidation = [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'sex' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ];

        $extraValidation = match ($type) {
            'professor' => ['grade' => 'required|string|max:255'],
            'student' => ['level_of_education' => 'required|string|max:255'],
            // 'administrator' => ['function' => 'required|string|max:255'],
            default => [],
        };

        return $request->validate(array_merge($baseValidation, $extraValidation));
    }

    protected function createUser(string $type, array $validated)
    {
        $commonData = [
            'firstname' => $validated['firstname'],
            'lastname' => $validated['lastname'],
            'sex' => $validated['sex'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $type,
        ];

        $extraData = match ($type) {
            'professor' => ['grade' => $validated['grade']],
            // 'student' => ['level_of_education' => $validated['level_of_education']],
            // 'administrator' => ['function' => $validated['function']],
            default => [],
        };

        return match ($type) {
            'professor' => Professors::create(array_merge($commonData, $extraData)),
            // 'student' => Students::create(array_merge($commonData, $extraData)),
            'administrator' => Administrators::create(array_merge($commonData, $extraData)),
            default => null,
        };
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


    //mofier son profil

    public function updateProfil(Request $request)
    {
        try {
            // Validation des données
            $baseValidation = [
                'firstname' => 'nullable|string|max:255',
                'lastname' => 'nullable|string|max:255',
                'sex' => 'nullable|string|max:255',
                'email' => 'nullable|string|email|max:255|unique:users,email,' . Auth::id(),
                // 'grade'=> 'nullable|string',
                // 'level_of_education' => 'nullable|string',
                // ''
            ];

            $type = Auth::user()->role; // On récupère le rôle de l'utilisateur authentifié (professeur, étudiant, administrateur)

            $extraValidation = match ($type) {
                'professor' => [
                    'grade' => 'nullable|string|max:255',
                ],
                'student' => [
                    'level_of_education' => 'nullable|string|max:255',
                ],
                // 'administrator' => [
                //     'function' => 'nullable|string|max:255',
                // ],
                default => [],
            };

            $validated = $request->validate(array_merge($baseValidation, $extraValidation));

            // Mise à jour de l'utilisateur
            $user = Auth::user(); // Récupérer l'utilisateur authentifié
            $user->update([
                'firstname' => $validated['firstname'] ?? $user->firstname,
                'lastname' => $validated['lastname'] ?? $user->lastname,
                'sex' => $validated['sex'] ?? $user->sex,
                'email' => $validated['email'] ?? $user->email,
                'password' => isset($validated['password']) ? Hash::make($validated['password']) : $user->password,
            ]);

            // Mise à jour selon le type d'utilisateur
            $data = match ($type) {
                'professor' => Professors::where('user_id', $user->id)->update([
                    'grade' => $validated['grade'] ?? Professors::where('user_id', $user->id)->value('grade'),
                ]),
                'student' => Students::where('user_id', $user->id)->update([
                    'level_of_education' => $validated['level_of_education'] ?? Students::where('user_id', $user->id)->value('level_of_education'),
                ]),
                // 'administrator' => Administrators::where('user_id', $user->id)->update([
                //     'function' => $validated['function'] ?? Administrators::where('user_id', $user->id)->value('function'),
                // ]),
                default => null,
            };

            return response()->json([
                'user' => $user,
                'data' => $data,
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        }
    }



}

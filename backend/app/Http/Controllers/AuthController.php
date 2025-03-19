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

    public function registerUser(Request $request)
    {
        try {
            $validated = $request->validate([
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'sex' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
            ], [
                'firstname.required' => 'Le prénom est obligatoire.',
                'lastname.required' => 'Le nom de famille est obligatoire.',
                'sex.required' => 'Le sexe est obligatoire.',
                'email.required' => 'L\'email est obligatoire.',
                'email.email' => 'L\'email doit être valide.',
                'email.unique' => 'L\'email existe déjà.',
                'password.required' => 'Le mot de passe est obligatoire.',
                'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
                'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            ]);

            $user = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'sex' => $request->sex,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            //prof


            //student


            //admin


            //sucesss register
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token
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


    //register Prof
    public function registerProf(Request $request){
        try {
            $validated = $request->validate([
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'sex' => 'required|string|max:255',
                'grade' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:6|confirmed',

            ], [
                'firstname.required' => 'Le prénom est obligatoire.',
                'lastname.required' => 'Le nom de famille est obligatoire.',
                'sex.required' => 'Le sexe est obligatoire.',
                'email.required' => 'L\'email est obligatoire.',
                'email.email' => 'L\'email doit être valide.',
                'email.unique' => 'L\'email existe déjà.',
                'password.required' => 'Le mot de passe est obligatoire.',
                'grade' =>  "Le grade est obligatoire",
                'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
                'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            ]);


            $user = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'sex' => $request->sex,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $grade =  $request->grade;
            $professor = Professors::create([
                "grade" => $grade,
                "user_id" => $user->id,
            ]);


            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'professor' => $professor,
                'token' => $token
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        }
    }

    //register Student
    public function registerStudent(Request $request){
        try {
            $validated = $request->validate([
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'sex' => 'required|string|max:255',
                'level_of_education' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:6|confirmed',

            ], [
                'firstname.required' => 'Le prénom est obligatoire.',
                'lastname.required' => 'Le nom de famille est obligatoire.',
                'sex.required' => 'Le sexe est obligatoire.',
                'email.required' => 'L\'email est obligatoire.',
                'email.email' => 'L\'email doit être valide.',
                'email.unique' => 'L\'email existe déjà.',
                'password.required' => 'Le mot de passe est obligatoire.',
                'level_of_education' => "Entrer votre niveau d'étude complet",
                'grade' =>  "Le grade est obligatoire",
                'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
                'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            ]);

            $user = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'sex' => $request->sex,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $professor = Students::create([
                "level_of_education" => $request->level_of_education,
                "grade" => $request->grade,
                "user_id" => $user->id,
            ]);


            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'professor' => $professor,
                'token' => $token
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        }
    }

    //register registerAdministrator
    public function registerAdministrator(Request $request){
        try {
            $validated = $request->validate([
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'sex' => 'required|string|max:255',
                'role' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:6|confirmed',

            ], [
                'firstname.required' => 'Le prénom est obligatoire.',
                'lastname.required' => 'Le nom de famille est obligatoire.',
                'sex.required' => 'Le sexe est obligatoire.',
                'email.required' => 'L\'email est obligatoire.',
                'email.email' => 'L\'email doit être valide.',
                'email.unique' => 'L\'email existe déjà.',
                'password.required' => 'Le mot de passe est obligatoire.',
                'role' => "Votre role est requis",
                'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
                'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            ]);

            $user = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'sex' => $request->sex,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $professor = Administrators::create([
                "role" => $request->role,
                "user_id" => $user->id,
            ]);


            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'professor' => $professor,
                'token' => $token
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        }
    }

}

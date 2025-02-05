<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Login a user and issue a token.
     */
    public function login(Request $request)
    {
        // Validation des champs d'entrée
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Vérifier si l'utilisateur existe
        $user = User::where('email', $credentials['email'])->first();

        // Vérifier le mot de passe
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'status'=> 'error',
                'message' => 'Invalid credentials.'
            ], 401);
        }

        // Créer un token pour l'utilisateur
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'succes',
            'message' => 'Connexion réussi.',
            'data'    => UserResource::collection($user),
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        // Validation des données d'entrée
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Création d'un nouvel utilisateur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash du mot de passe
        ]);

        // Créer un token pour l'utilisateur
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'succes',
            'message' => 'User registered successfully.',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    /**
     * Logout the authenticated user and revoke their token.
     */
    public function logout(Request $request)
    {
        // Vérifier si l'utilisateur est authentifié
        if (!$request->user()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Utilisateur non authentifié. Veuillez vous connecter.',
            ], 401);
        }
    
        // Supprimer le token d'accès actuel
        $request->user()->currentAccessToken()->delete();
    
        return response()->json([
            'status' => 'success',
            'message' => 'Déconnexion réussie.',
        ]);
    }
    
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * REGISTER — Créer un nouveau compte
     * POST /api/register
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            // Forcer le rôle à 'serveur' (seul un admin peut changer le rôle d'un utilisateur)
            $data = $request->validated();
            $data['role'] = 'serveur';

            // Créer l'utilisateur (mot de passe hashé automatiquement grâce au cast 'hashed')
            $user = User::create($data);

            // Créer un token pour cet utilisateur
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Inscription réussie.',
                'user' => new UserResource($user),
                'token' => $token,
            ], 201);

        } catch (\Throwable $e) {
            if (app()->environment('production')) {
                return response()->json([
                    'message' => 'Erreur interne du serveur. Veuillez réessayer plus tard.'
                ], 500);
            }
            return response()->json([
                'message' => 'Erreur lors de l\'inscription.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * LOGIN — Se connecter
     * POST /api/login
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            // Chercher l'utilisateur par email
            $user = User::where('email', $request->email)->first();

            // Vérifier que l'utilisateur existe ET que le mot de passe est correct
            if (!$user || !Hash::check($request->mot_de_passe, $user->mot_de_passe)) {
                return response()->json([
                    'message' => 'Email ou mot de passe incorrect.'
                ], 401);
            }

            // Créer un token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Connexion réussie.',
                'user' => new UserResource($user),
                'token' => $token,
            ]);

        } catch (\Throwable $e) {
            if (app()->environment('production')) {
                return response()->json([
                    'message' => 'Erreur interne du serveur. Veuillez réessayer plus tard.'
                ], 500);
            }
            return response()->json([
                'message' => 'Erreur lors de la connexion.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * LOGOUT — Se déconnecter
     * POST /api/logout
     */
    public function logout(Request $request): JsonResponse
    {
        // Supprime le token utilisé pour cette requête
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie.'
        ]);
    }

    /**
     * USER — Voir mon profil
     * GET /api/user
     */
    public function user(Request $request): JsonResponse
    {
        return (new UserResource($request->user()))->response();
    }
}

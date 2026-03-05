<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRoleRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Liste tous les utilisateurs (admin seulement)
     * GET /api/users
     */
    public function index(): JsonResponse
    {
        $users = User::paginate(25);
        return UserResource::collection($users)->response();
    }

    /**
     * Affiche un utilisateur spécifique
     * GET /api/users/{user}
     */
    public function show(User $user): JsonResponse
    {
        return (new UserResource($user))->response();
    }

    /**
     * Modifier le rôle d'un utilisateur
     * PUT /api/users/{user}/role
     */
    public function updateRole(UpdateUserRoleRequest $request, User $user): JsonResponse
    {
        // Empêcher un admin de se rétrograder lui-même
        if ($user->id === $request->user()->id) {
            return response()->json([
                'message' => 'Vous ne pouvez pas modifier votre propre rôle.',
            ], 403);
        }

        $user->role = $request->validated()['role'];
        $user->save();

        return response()->json([
            'message' => 'Rôle mis à jour avec succès.',
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Supprimer un utilisateur (soft delete)
     * DELETE /api/users/{user}
     */
    public function destroy(Request $request, User $user): JsonResponse
    {
        // Empêcher un admin de se supprimer lui-même
        if ($user->id === $request->user()->id) {
            return response()->json([
                'message' => 'Vous ne pouvez pas supprimer votre propre compte.',
            ], 403);
        }

        try {
            $user->delete();
            return response()->json(['message' => 'Utilisateur supprimé avec succès.']);
        } catch (\Throwable $e) {
            if (app()->environment('production')) {
                return response()->json([
                    'message' => 'Erreur interne du serveur. Veuillez réessayer plus tard.'
                ], 500);
            }
            return response()->json([
                'message' => 'Erreur lors de la suppression.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fournisseur\StoreFournisseurRequest;
use App\Http\Requests\Fournisseur\UpdateFournisseurRequest;
use App\Http\Resources\FournisseurResource;
use App\Models\Fournisseur;
use Illuminate\Http\JsonResponse;

class FournisseurController extends Controller
{
    /**
     * Liste tous les fournisseurs.
     * GET /api/fournisseurs
     */
    public function index(): JsonResponse
    {
        // Récupère tous les fournisseurs paginés
        $fournisseurs = Fournisseur::paginate(25);
        // Retourne la liste paginée transformée par FournisseurResource
        return FournisseurResource::collection($fournisseurs)->response();
    }

    /**
     * Crée un nouveau fournisseur.
     * POST /api/fournisseurs
     */
    public function store(StoreFournisseurRequest $request): JsonResponse
    {
        try {
            // Crée un nouveau fournisseur avec les données validées
            $fournisseur = Fournisseur::create($request->validated());
            // Retourne le fournisseur créé transformé par FournisseurResource
            return (new FournisseurResource($fournisseur))->response()->setStatusCode(201);
        } catch (\Throwable $e) {
            // Gestion d'erreur serveur
            if (app()->environment('production')) {
                return response()->json([
                    'message' => 'Erreur interne du serveur. Veuillez réessayer plus tard.'
                ], 500);
            }
            // Retourne le message d'erreur détaillé en dev
            return response()->json([
                'message' => 'Erreur lors de la création du fournisseur.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Affiche un fournisseur spécifique.
     * GET /api/fournisseurs/{fournisseur}
     */
    public function show(Fournisseur $fournisseur): JsonResponse
    {
        // Retourne le fournisseur transformé par FournisseurResource
        return (new FournisseurResource($fournisseur))->response();
    }

    /**
     * Met à jour un fournisseur existant.
     * PUT /api/fournisseurs/{fournisseur}
     */
    public function update(UpdateFournisseurRequest $request, Fournisseur $fournisseur): JsonResponse
    {
        try {
            // Met à jour le fournisseur avec les données validées
            $fournisseur->update($request->validated());
            // Retourne le fournisseur mis à jour transformé par FournisseurResource
            return (new FournisseurResource($fournisseur))->response();
        } catch (\Throwable $e) {
            // Gestion d'erreur serveur
            if (app()->environment('production')) {
                return response()->json([
                    'message' => 'Erreur interne du serveur. Veuillez réessayer plus tard.'
                ], 500);
            }
            // Retourne le message d'erreur détaillé en dev
            return response()->json([
                'message' => 'Erreur lors de la mise à jour du fournisseur.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprime un fournisseur.
     * DELETE /api/fournisseurs/{fournisseur}
     */
    public function destroy(Fournisseur $fournisseur): JsonResponse
    {
        try {
            // Supprime le fournisseur
            $fournisseur->delete();
            // Retourne un message de succès
            return response()->json(['message' => 'Fournisseur supprimé avec succès.']);
        } catch (\Throwable $e) {
            // Gestion d'erreur serveur
            if (app()->environment('production')) {
                return response()->json([
                    'message' => 'Erreur interne du serveur. Veuillez réessayer plus tard.'
                ], 500);
            }
            // Retourne le message d'erreur détaillé en dev
            return response()->json([
                'message' => 'Erreur lors de la suppression du fournisseur.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

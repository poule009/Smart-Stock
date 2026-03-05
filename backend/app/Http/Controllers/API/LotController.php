<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lot\StoreLotRequest;
use App\Http\Requests\Lot\UpdateLotRequest;
use App\Http\Resources\LotResource;
use App\Models\Lot;
use Illuminate\Http\JsonResponse;

class LotController extends Controller
{
    /**
     * Liste tous les lots.
     * GET /api/lots
     */
    public function index(): JsonResponse
    {
        // Récupère tous les lots paginés avec leur produit associé
        $lots = Lot::with('produit')->paginate(25);
        // Retourne la liste paginée transformée par LotResource
        return LotResource::collection($lots)->response();
    }

    /**
     * Crée un nouveau lot.
     * POST /api/lots
     */
    public function store(StoreLotRequest $request): JsonResponse
    {
        try {
            // Crée un nouveau lot avec les données validées
            $lot = Lot::create($request->validated());
            // Initialiser quantite_actuelle = quantite_initiale
            $lot->quantite_actuelle = $lot->quantite_initiale;
            $lot->save();
            // Charge la relation produit pour la réponse
            $lot->load('produit');
            // Retourne le lot créé transformé par LotResource
            return (new LotResource($lot))->response()->setStatusCode(201);
        } catch (\Throwable $e) {
            // Gestion d'erreur serveur
            if (app()->environment('production')) {
                return response()->json([
                    'message' => 'Erreur interne du serveur. Veuillez réessayer plus tard.'
                ], 500);
            }
            // Retourne le message d'erreur détaillé en dev
            return response()->json([
                'message' => 'Erreur lors de la création du lot.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Affiche un lot spécifique.
     * GET /api/lots/{lot}
     */
    public function show(Lot $lot): JsonResponse
    {
        // Charge la relation produit pour ce lot
        $lot->load('produit');
        // Retourne le lot transformé par LotResource
        return (new LotResource($lot))->response();
    }

    /**
     * Met à jour un lot existant.
     * PUT /api/lots/{lot}
     */
    public function update(UpdateLotRequest $request, Lot $lot): JsonResponse
    {
        try {
            // Met à jour le lot avec les données validées
            $lot->update($request->validated());
            // Recharge la relation produit pour la réponse
            $lot->load('produit');
            // Retourne le lot mis à jour transformé par LotResource
            return (new LotResource($lot))->response();
        } catch (\Throwable $e) {
            // Gestion d'erreur serveur
            if (app()->environment('production')) {
                return response()->json([
                    'message' => 'Erreur interne du serveur. Veuillez réessayer plus tard.'
                ], 500);
            }
            // Retourne le message d'erreur détaillé en dev
            return response()->json([
                'message' => 'Erreur lors de la mise à jour du lot.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprime un lot.
     * DELETE /api/lots/{lot}
     */
    public function destroy(Lot $lot): JsonResponse
    {
        try {
            // Supprime le lot
            $lot->delete();
            // Retourne un message de succès
            return response()->json(['message' => 'Lot supprimé avec succès.']);
        } catch (\Throwable $e) {
            // Gestion d'erreur serveur
            if (app()->environment('production')) {
                return response()->json([
                    'message' => 'Erreur interne du serveur. Veuillez réessayer plus tard.'
                ], 500);
            }
            // Retourne le message d'erreur détaillé en dev
            return response()->json([
                'message' => 'Erreur lors de la suppression du lot.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

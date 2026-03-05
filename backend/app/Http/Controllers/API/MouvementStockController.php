<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MouvementStock\StoreMouvementStockRequest;
use App\Http\Requests\MouvementStock\UpdateMouvementStockRequest;
use App\Http\Resources\MouvementStockResource;
use App\Models\Lot;
use App\Models\Mouvement_Stock;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MouvementStockController extends Controller
{
    /**
     * Liste tous les mouvements de stock.
     * GET /api/mouvements-stock
     */
    public function index(): JsonResponse
    {
        $mouvements = Mouvement_Stock::with(['lot.produit', 'user'])->paginate(25);
        return MouvementStockResource::collection($mouvements)->response();
    }

    /**
     * Crée un nouveau mouvement de stock et met à jour la quantité du lot.
     * POST /api/mouvements-stock
     */
    public function store(StoreMouvementStockRequest $request): JsonResponse
    {
        try {
            $result = DB::transaction(function () use ($request) {
                // 1. Verrouiller le lot pour éviter les conflits
                $lot = Lot::lockForUpdate()->findOrFail($request->lot_id);

                // 2. Si c'est une sortie, vérifier qu'on a assez de stock
                if ($request->type === 'sortie') {
                    if ($lot->quantite_actuelle < $request->quantite) {
                        throw new \InvalidArgumentException(
                            "Stock insuffisant. Disponible : {$lot->quantite_actuelle}, Demandé : {$request->quantite}"
                        );
                    }
                }

                // 3. Créer le mouvement
                $mouvement = Mouvement_Stock::create(
                    $request->validated() + ['user_id' => Auth::id()]
                );

                // 4. Mettre à jour la quantité actuelle du lot
                if ($request->type === 'entree') {
                    $lot->quantite_actuelle += $request->quantite;
                } else {
                    $lot->quantite_actuelle -= $request->quantite;
                }

                // 5. Sauvegarder le lot (le boot() du model vérifie aussi que quantite >= 0)
                $lot->save();

                // 6. Charger les relations pour la réponse
                $mouvement->load(['lot.produit', 'user']);

                return $mouvement;
            });

            return (new MouvementStockResource($result))->response()->setStatusCode(201);

        } catch (\InvalidArgumentException $e) {
            // Erreur métier (stock insuffisant)
            return response()->json([
                'message' => $e->getMessage()
            ], 422);

        } catch (\Throwable $e) {
            // Gestion d'erreur serveur
            if (app()->environment('production')) {
                return response()->json([
                    'message' => 'Erreur interne du serveur. Veuillez réessayer plus tard.'
                ], 500);
            }
            // Retourne le message d'erreur détaillé en dev
            return response()->json([
                'message' => 'Erreur lors de la création du mouvement de stock.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Affiche un mouvement de stock spécifique.
     * GET /api/mouvements-stock/{mouvement_stock}
     */
    public function show(Mouvement_Stock $mouvement_stock): JsonResponse
    {
        // Charge les relations lot, produit et utilisateur pour ce mouvement
        $mouvement_stock->load(['lot.produit', 'user']);
        // Retourne le mouvement transformé par MouvementStockResource
        return (new MouvementStockResource($mouvement_stock))->response();
    }

    /**
     * Met à jour un mouvement de stock existant.
     * PUT /api/mouvements-stock/{mouvement_stock}
     */
    public function update(UpdateMouvementStockRequest $request, Mouvement_Stock $mouvement_stock): JsonResponse
    {
        try {
            $result = DB::transaction(function () use ($request, $mouvement_stock) {
                // 1. Reverser l'ancien mouvement sur l'ancien lot
                $ancienLot = Lot::lockForUpdate()->findOrFail($mouvement_stock->lot_id);
                if ($mouvement_stock->type === 'entree') {
                    $ancienLot->quantite_actuelle -= $mouvement_stock->quantite;
                } else {
                    $ancienLot->quantite_actuelle += $mouvement_stock->quantite;
                }
                $ancienLot->save();

                // 2. Appliquer le nouveau mouvement sur le (nouveau) lot
                $nouveauLotId = $request->lot_id ?? $mouvement_stock->lot_id;
                $nouveauLot = ($nouveauLotId === $ancienLot->id)
                    ? $ancienLot
                    : Lot::lockForUpdate()->findOrFail($nouveauLotId);

                if ($request->type === 'sortie') {
                    if ($nouveauLot->quantite_actuelle < $request->quantite) {
                        throw new \InvalidArgumentException(
                            "Stock insuffisant. Disponible : {$nouveauLot->quantite_actuelle}, Demandé : {$request->quantite}"
                        );
                    }
                }

                if ($request->type === 'entree') {
                    $nouveauLot->quantite_actuelle += $request->quantite;
                } else {
                    $nouveauLot->quantite_actuelle -= $request->quantite;
                }
                $nouveauLot->save();

                // 3. Mettre à jour le mouvement
                $mouvement_stock->update($request->validated());
                $mouvement_stock->load(['lot.produit', 'user']);

                return $mouvement_stock;
            });

            return (new MouvementStockResource($result))->response();

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);

        } catch (\Throwable $e) {
            // Gestion d'erreur serveur
            if (app()->environment('production')) {
                return response()->json([
                    'message' => 'Erreur interne du serveur. Veuillez réessayer plus tard.'
                ], 500);
            }
            // Retourne le message d'erreur détaillé en dev
            return response()->json([
                'message' => 'Erreur lors de la mise à jour du mouvement de stock.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprime un mouvement de stock.
     * DELETE /api/mouvements-stock/{mouvement_stock}
     */
    public function destroy(Mouvement_Stock $mouvement_stock): JsonResponse
    {
        try {
            DB::transaction(function () use ($mouvement_stock) {
                // 1. Reverser l'impact du mouvement sur le lot
                $lot = Lot::lockForUpdate()->findOrFail($mouvement_stock->lot_id);
                if ($mouvement_stock->type === 'entree') {
                    $lot->quantite_actuelle -= $mouvement_stock->quantite;
                } else {
                    $lot->quantite_actuelle += $mouvement_stock->quantite;
                }
                $lot->save();

                // 2. Supprimer le mouvement
                $mouvement_stock->delete();
            });

            return response()->json(['message' => 'Mouvement de stock supprimé avec succès.']);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);

        } catch (\Throwable $e) {
            // Gestion d'erreur serveur
            if (app()->environment('production')) {
                return response()->json([
                    'message' => 'Erreur interne du serveur. Veuillez réessayer plus tard.'
                ], 500);
            }
            // Retourne le message d'erreur détaillé en dev
            return response()->json([
                'message' => 'Erreur lors de la suppression du mouvement de stock.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

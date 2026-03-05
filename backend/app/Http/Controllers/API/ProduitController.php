<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Produit\StoreProduitRequest;
use App\Http\Requests\Produit\UpdateProduitRequest;
use App\Http\Resources\ProduitResource;
use App\Models\Produit;
use Illuminate\Http\JsonResponse;

class ProduitController extends Controller
{
    /**
     * Liste tous les produits.
     * GET /api/produits
     */
    public function index(): JsonResponse
    {
        // Récupère tous les produits paginés avec leur catégorie et fournisseur
        $produits = Produit::with(['categorie', 'fournisseur', 'lots'])->paginate(25);
        // Retourne la liste paginée transformée par ProduitResource
        return ProduitResource::collection($produits)->response();
    }

    /**
     * Crée un nouveau produit.
     * POST /api/produits
     */
    public function store(StoreProduitRequest $request): JsonResponse
    {
        try {
            // Crée un nouveau produit avec les données validées
            $produit = Produit::create($request->validated());
            // Charge les relations pour la réponse
            $produit->load(['categorie', 'fournisseur']);
            // Retourne le produit créé transformé par ProduitResource
            return (new ProduitResource($produit))->response()->setStatusCode(201);
        } catch (\Throwable $e) {
            // Gestion d'erreur serveur
            if (app()->environment('production')) {
                return response()->json([
                    'message' => 'Erreur interne du serveur. Veuillez réessayer plus tard.'
                ], 500);
            }
            // Retourne le message d'erreur détaillé en dev
            return response()->json([
                'message' => 'Erreur lors de la création du produit.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Affiche un produit spécifique.
     * GET /api/produits/{produit}
     */
    public function show(Produit $produit): JsonResponse
    {
        // Charge les relations catégorie, fournisseur et lots pour ce produit
        $produit->load(['categorie', 'fournisseur', 'lots']);
        // Retourne le produit transformé par ProduitResource
        return (new ProduitResource($produit))->response();
    }

    /**
     * Met à jour un produit existant.
     * PUT /api/produits/{produit}
     */
    public function update(UpdateProduitRequest $request, Produit $produit): JsonResponse
    {
        try {
            // Met à jour le produit avec les données validées
            $produit->update($request->validated());
            // Recharge les relations pour la réponse
            $produit->load(['categorie', 'fournisseur']);
            // Retourne le produit mis à jour transformé par ProduitResource
            return (new ProduitResource($produit))->response();
        } catch (\Throwable $e) {
            // Gestion d'erreur serveur
            if (app()->environment('production')) {
                return response()->json([
                    'message' => 'Erreur interne du serveur. Veuillez réessayer plus tard.'
                ], 500);
            }
            // Retourne le message d'erreur détaillé en dev
            return response()->json([
                'message' => 'Erreur lors de la mise à jour du produit.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprime un produit.
     * DELETE /api/produits/{produit}
     */
    public function destroy(Produit $produit): JsonResponse
    {
        try {
            // Supprime le produit
            $produit->delete();
            // Retourne un message de succès
            return response()->json(['message' => 'Produit supprimé avec succès.']);
        } catch (\Throwable $e) {
            // Gestion d'erreur serveur
            if (app()->environment('production')) {
                return response()->json([
                    'message' => 'Erreur interne du serveur. Veuillez réessayer plus tard.'
                ], 500);
            }
            // Retourne le message d'erreur détaillé en dev
            return response()->json([
                'message' => 'Erreur lors de la suppression du produit.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

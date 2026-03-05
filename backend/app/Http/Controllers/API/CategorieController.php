<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Categorie\StoreCategoryRequest;
use App\Http\Requests\Categorie\UpdateCategoryRequest;
use App\Http\Resources\CategorieResource;
use App\Models\Categorie;
use Illuminate\Http\JsonResponse;

class CategorieController extends Controller
{
    /**
     * Liste toutes les catégories.
     * GET /api/categories
     */
    public function index(): JsonResponse
    {
        // Récupère toutes les catégories paginées avec le nombre de produits
        $categories = Categorie::with('produits')->paginate(25);
        // Retourne la liste paginée transformée par CategorieResource
        return CategorieResource::collection($categories)->response();
    }

    /**
     * Crée une nouvelle catégorie.
     * POST /api/categories
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        try {
            // Crée une nouvelle catégorie avec les données validées
            $categorie = Categorie::create($request->validated());
            // Charge les produits pour la réponse
            $categorie->load('produits');
            // Retourne la catégorie créée transformée par CategorieResource
            return (new CategorieResource($categorie))->response()->setStatusCode(201);
        } catch (\Throwable $e) {
            // Gestion d'erreur serveur
            if (app()->environment('production')) {
                return response()->json([
                    'message' => 'Erreur interne du serveur. Veuillez réessayer plus tard.'
                ], 500);
            }
            // Retourne le message d'erreur détaillé en dev
            return response()->json([
                'message' => 'Erreur lors de la création de la catégorie.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Affiche une catégorie spécifique.
     * GET /api/categories/{categorie}
     */
    public function show(Categorie $categorie): JsonResponse
    {
        // Charge les produits pour afficher le nombre
        $categorie->load('produits');
        // Retourne la catégorie transformée par CategorieResource
        return (new CategorieResource($categorie))->response();
    }

    /**
     * Met à jour une catégorie existante.
     * PUT /api/categories/{categorie}
     */
    public function update(UpdateCategoryRequest $request, Categorie $categorie): JsonResponse
    {
        try {
            // Met à jour la catégorie avec les données validées
            $categorie->update($request->validated());
            // Recharge les produits pour la réponse
            $categorie->load('produits');
            // Retourne la catégorie mise à jour transformée par CategorieResource
            return (new CategorieResource($categorie))->response();
        } catch (\Throwable $e) {
            // Gestion d'erreur serveur
            if (app()->environment('production')) {
                return response()->json([
                    'message' => 'Erreur interne du serveur. Veuillez réessayer plus tard.'
                ], 500);
            }
            // Retourne le message d'erreur détaillé en dev
            return response()->json([
                'message' => 'Erreur lors de la mise à jour de la catégorie.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprime une catégorie.
     * DELETE /api/categories/{categorie}
     */
    public function destroy(Categorie $categorie): JsonResponse
    {
        try {
            // Vérifie qu'aucun produit n'est rattaché à cette catégorie
            if ($categorie->produits()->exists()) {
                return response()->json([
                    'message' => 'Impossible de supprimer cette catégorie car des produits y sont rattachés.'
                ], 409);
            }
            // Supprime la catégorie
            $categorie->delete();
            // Retourne un message de succès
            return response()->json(['message' => 'Catégorie supprimée avec succès.']);
        } catch (\Throwable $e) {
            // Gestion d'erreur serveur
            if (app()->environment('production')) {
                return response()->json([
                    'message' => 'Erreur interne du serveur. Veuillez réessayer plus tard.'
                ], 500);
            }
            // Retourne le message d'erreur détaillé en dev
            return response()->json([
                'message' => 'Erreur lors de la suppression de la catégorie.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

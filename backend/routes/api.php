<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategorieController;
use App\Http\Controllers\API\FournisseurController;
use App\Http\Controllers\API\ProduitController;
use App\Http\Controllers\API\LotController;
use App\Http\Controllers\API\MouvementStockController;
use App\Http\Controllers\API\UserController;


Route::middleware('throttle:5,1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});


Route::middleware('auth:sanctum')->group(function () {

    // --- Auth (tous les rôles) ---
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // ═══════════════════════════════════════════════════════
    // LECTURE SEULE — Tous les rôles peuvent VOIR
    // ═══════════════════════════════════════════════════════
    Route::get('/categories', [CategorieController::class, 'index']);
    Route::get('/categories/{categorie}', [CategorieController::class, 'show']);

    Route::get('/fournisseurs', [FournisseurController::class, 'index']);
    Route::get('/fournisseurs/{fournisseur}', [FournisseurController::class, 'show']);

    Route::get('/produits', [ProduitController::class, 'index']);
    Route::get('/produits/{produit}', [ProduitController::class, 'show']);

    Route::get('/lots', [LotController::class, 'index']);
    Route::get('/lots/{lot}', [LotController::class, 'show']);

    Route::get('/mouvements-stock', [MouvementStockController::class, 'index']);
    Route::get('/mouvements-stock/{mouvement_stock}', [MouvementStockController::class, 'show']);

    // ═══════════════════════════════════════════════════════
    // MOUVEMENTS DE STOCK — Créer : tous | Modifier/Supprimer : admin
    // ═══════════════════════════════════════════════════════
    Route::post('/mouvements-stock', [MouvementStockController::class, 'store']);

    Route::middleware('role:admin')->group(function () {
        Route::put('/mouvements-stock/{mouvement_stock}', [MouvementStockController::class, 'update']);
        Route::delete('/mouvements-stock/{mouvement_stock}', [MouvementStockController::class, 'destroy']);
    });

    // ═══════════════════════════════════════════════════════
    // CATÉGORIES & FOURNISSEURS & UTILISATEURS — Admin seulement
    // ═══════════════════════════════════════════════════════
    Route::middleware('role:admin')->group(function () {
        Route::post('/categories', [CategorieController::class, 'store']);
        Route::put('/categories/{categorie}', [CategorieController::class, 'update']);
        Route::delete('/categories/{categorie}', [CategorieController::class, 'destroy']);

        Route::post('/fournisseurs', [FournisseurController::class, 'store']);
        Route::put('/fournisseurs/{fournisseur}', [FournisseurController::class, 'update']);
        Route::delete('/fournisseurs/{fournisseur}', [FournisseurController::class, 'destroy']);

        // Gestion des utilisateurs (admin seulement)
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::put('/users/{user}/role', [UserController::class, 'updateRole']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);
    });

    // ═══════════════════════════════════════════════════════
    // PRODUITS & LOTS — Admin + Cuisinier
    // ═══════════════════════════════════════════════════════
    Route::middleware('role:admin,cuisinier')->group(function () {
        Route::post('/produits', [ProduitController::class, 'store']);
        Route::put('/produits/{produit}', [ProduitController::class, 'update']);
        Route::delete('/produits/{produit}', [ProduitController::class, 'destroy']);

        Route::post('/lots', [LotController::class, 'store']);
        Route::put('/lots/{lot}', [LotController::class, 'update']);
        Route::delete('/lots/{lot}', [LotController::class, 'destroy']);
    });
});

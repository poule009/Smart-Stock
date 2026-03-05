<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Ajouter le middleware CORS pour les requêtes API
        $middleware->api(prepend: [
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);

        // Configurer Sanctum pour les requêtes stateful (cookies)
        $middleware->statefulApi();

        // Alias du middleware de vérification de rôle
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // ═══════════════════════════════════════════════════════
        // ① ERREUR 422 — Validation échouée
        // ═════════════════════════════════════════════════════════
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*') || $request->wantsJson()) {
                return response()->json([
                    'message' => 'Les données fournies sont invalides.',
                    'errors' => $e->errors(),
                ], 422);
            }
        });

        // ═════════════════════════════════════════════════════════
        // ② ERREUR 404 — Route ou Model introuvable
        // ═══════════════════════════════════════════════════════
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*') || $request->wantsJson()) {
                return response()->json([
                    'message' => 'Ressource non trouvée.'
                ], 404);
            }
        });

        // ═══════════════════════════════════════════════════════
        // ③ ERREUR 404 — Model spécifiquement introuvable
        // (quand findOrFail() ou Route Model Binding échoue)
        // ═══════════════════════════════════════════════════════
        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            if ($request->is('api/*') || $request->wantsJson()) {
                $model = class_basename($e->getModel());

                return response()->json([
                    'message' => "{$model} non trouvé(e)."
                ], 404);
            }
        });

        // ═══════════════════════════════════════════════════════
        // ④ ERREUR 401 — Non authentifié
        // ═══════════════════════════════════════════════════════
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*') || $request->wantsJson()) {
                return response()->json([
                    'message' => 'Non authentifié. Veuillez vous connecter.'
                ], 401);
            }
        });

        // ═══════════════════════════════════════════════════════
        // ⑤ ERREUR 405 — Méthode HTTP non autorisée
        // (ex: faire un GET sur une route qui n'accepte que POST)
        // ═══════════════════════════════════════════════════════
        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {
            if ($request->is('api/*') || $request->wantsJson()) {
                return response()->json([
                    'message' => 'Méthode HTTP non autorisée pour cette route.'
                ], 405);
            }
        });

    })->create();

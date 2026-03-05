<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Chemins concernés par CORS
    |--------------------------------------------------------------------------
    |
    | Applique CORS uniquement aux routes qui commencent par 'api/'
    | et à la route spéciale de Sanctum pour les cookies CSRF.
    |
    */
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    /*
    |--------------------------------------------------------------------------
    | Méthodes HTTP autorisées
    |--------------------------------------------------------------------------
    */
    'allowed_methods' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Origines autorisées
    |--------------------------------------------------------------------------
    |
    | Seuls ces sites peuvent appeler l'API.
    | En production, ajouter l'URL du front déployé.
    | Ne PAS mettre '*' avec supports_credentials = true.
    |
    */
    'allowed_origins' => [
        'http://localhost:4200',
        'http://localhost:4000',
    ],

    /*
    |--------------------------------------------------------------------------
    | Patterns d'origines (regex)
    |--------------------------------------------------------------------------
    */
    'allowed_origins_patterns' => [],

    /*
    |--------------------------------------------------------------------------
    | Headers autorisés
    |--------------------------------------------------------------------------
    */
    'allowed_headers' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Headers exposés au client
    |--------------------------------------------------------------------------
    */
    'exposed_headers' => [],

    /*
    |--------------------------------------------------------------------------
    | Durée de vie du preflight (en secondes)
    |--------------------------------------------------------------------------
    |
    | 0 = pas de cache (bien pour le dev).
    | En production, mettre 86400 (24h).
    |
    */
    'max_age' => 0,

    /*
    |--------------------------------------------------------------------------
    | Supports credentials (cookies, tokens)
    |--------------------------------------------------------------------------
    |
    | Nécessaire pour Sanctum si on utilise les cookies de session.
    | Quand true, allowed_origins ne peut PAS être '*'.
    |
    */
    'supports_credentials' => true,

];

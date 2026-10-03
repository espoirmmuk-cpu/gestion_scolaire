<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Synchronisation GESCO
    |--------------------------------------------------------------------------
    */

    'enabled' => env('SYNC_ENABLED', false),
    'device_id' => env('SYNC_DEVICE_ID'),

    /*
    | URL de l'API GESCO Cloud.
    |
    | Exemple :
    | https://gesco.example.com/api/sync
    */
    'url' => env('SYNC_URL'),

    /*
    | Jeton utilisé pour authentifier le poste Local
    | auprès du serveur Cloud.
    */
    'token' => env('SYNC_TOKEN'),

    /*
    | Nombre maximum d'opérations envoyées par lot.
    */
    'batch_size' => (int) env('SYNC_BATCH_SIZE', 100),

    /*
    | Nombre de secondes avant expiration d'une requête HTTP.
    */
    'timeout' => (int) env('SYNC_TIMEOUT', 30),

];
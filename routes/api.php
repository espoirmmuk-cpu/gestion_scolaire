<?php

use App\Http\Controllers\Api\SyncController;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API GESCO opérationnelle',
    ]);
});

Route::post('/sync/ping', [SyncController::class, 'ping']);
Route::post('/sync/push', [SyncController::class, 'push']);
Route::post('/sync/bootstrap', [SyncController::class, 'bootstrap']);
Route::post('/sync/pull', [SyncController::class, 'pull']);
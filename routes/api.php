<?php

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\BrowsingHistoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/login', [ApiAuthController::class, 'login']);

// Public Extension Handshake
Route::post('/extension/link', [\App\Http\Controllers\Api\Extension\LinkController::class, 'link']);

// External Content Ingestion (Public API)
Route::post('/external/content', [\App\Http\Controllers\Api\ExternalContentController::class, 'store']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [ApiAuthController::class, 'user']);
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    
    // Extension Link & Auth
    Route::post('/extension/unlink', [\App\Http\Controllers\Api\Extension\LinkController::class, 'unlink']);
    
    // Extension Activity & Metrics
    Route::post('/extension/sessions', [\App\Http\Controllers\Api\Extension\ActivityController::class, 'storeSession']);
    Route::post('/extension/metrics-snapshots', [\App\Http\Controllers\Api\Extension\ActivityController::class, 'storeMetricsSnapshot']);
    Route::post('/extension/daily-rollups', [\App\Http\Controllers\Api\Extension\ActivityController::class, 'storeDailyRollup']);
    Route::post('/extension/recommendations/contextual', [\App\Http\Controllers\Api\Extension\ActivityController::class, 'generateContextualRecommendation']);
    Route::post('/extension/recommendation-events', [\App\Http\Controllers\Api\Extension\ActivityController::class, 'storeRecommendationEvent']);
    Route::post('/extension/ask-help', [\App\Http\Controllers\Api\Extension\ActivityController::class, 'askHelp']);

    // History sync routes
    Route::post('/sync-history', [BrowsingHistoryController::class, 'sync']);
    Route::get('/fetch-history', [BrowsingHistoryController::class, 'fetch']);
    Route::delete('/clear-history', [BrowsingHistoryController::class, 'clear']);
});

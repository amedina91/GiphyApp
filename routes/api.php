<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\GiphyController;
use App\Http\Controllers\LogController;
use Laravel\Passport\Http\Controllers\{
    AccessTokenController,
    AuthorizedAccessTokenController,
    ClientController,
    ScopeController,
    PersonalAccessTokenController,
};

// Rutas de inicio de sesión y registro
Route::post('login', [LoginController::class,'login']);
Route::post('register', [RegisterController::class,'register']);

Route::middleware('auth:api')->group(function () {

    Route::prefix('user')->group(function(){
        Route::get('/all','App\Http\Controllers\UserController@all');
    });

    Route::get('giphy/search', [GiphyController::class, 'search']);
    Route::get('giphy/{id}', [GiphyController::class, 'show']);
    Route::post('giphy/favorites', [GiphyController::class, 'storeFavorite']);

    Route::get('logs', [LogController::class, 'index']);

    Route::get('/oauth/scopes', [ScopeController::class, 'all']);
    Route::post('/oauth/token', [AccessTokenController::class, 'issueToken'])->middleware('throttle');
    Route::get('/oauth/tokens', [AuthorizedAccessTokenController::class, 'forUser']);
    Route::delete('/oauth/tokens/{token_id}', [AuthorizedAccessTokenController::class, 'destroy']);
    Route::post('/oauth/tokens/{token_id}/revoke', [AuthorizedAccessTokenController::class, 'update']);
    Route::get('/oauth/clients', [ClientController::class, 'forUser']);
    Route::post('/oauth/clients', [ClientController::class, 'store']);
    Route::put('/oauth/clients/{client_id}', [ClientController::class, 'update']);
    Route::delete('/oauth/clients/{client_id}', [ClientController::class, 'destroy']);
    Route::get('/oauth/personal-access-tokens', [PersonalAccessTokenController::class, 'forUser']);
    Route::post('/oauth/personal-access-tokens', [PersonalAccessTokenController::class, 'store']);
    Route::delete('/oauth/personal-access-tokens/{token_id}', [PersonalAccessTokenController::class, 'destroy']);
});

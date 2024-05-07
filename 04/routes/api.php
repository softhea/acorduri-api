<?php

use App\Http\Controllers\ArtistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/artists', [ArtistController::class, 'store'])
    ->name('artists.store')
    ->middleware('auth:sanctum');

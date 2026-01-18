<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ArtistController;
use App\Http\Controllers\Api\AlbumController;
use App\Http\Controllers\Api\SongController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});


Route::get('artists', [ArtistController::class, 'index']);
Route::get('artists/{id}', [ArtistController::class, 'show']);
Route::post('artists', [ArtistController::class, 'store'])->middleware('auth:api');


Route::post('albums', [AlbumController::class, 'store'])->middleware('auth:api');


Route::get('songs', [SongController::class, 'index']);
Route::get('songs/{id}', [SongController::class, 'show']);
Route::post('songs', [SongController::class, 'store'])->middleware('auth:api');

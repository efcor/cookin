<?php

use App\Http\Controllers\DbController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RecipeController;
use App\Models\ApiLog;
use Illuminate\Support\Facades\Route;

Route::get('/login', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'doLogin']);

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [RecipeController::class, 'index']);

    Route::post('/generate-recipe', [RecipeController::class, 'generate']);

    Route::get('/db', [DbController::class, 'index']);
});

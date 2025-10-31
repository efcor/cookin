<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RecipeController;
use App\Models\ApiLog;
use Illuminate\Support\Facades\Route;

// Route::get('/', [ChatController::class, 'ask']);

Route::get('/', [RecipeController::class, 'index']);

Route::get('/login', [LoginController::class, 'index']);

Route::get('/db', function () {
    return ApiLog::all();
});

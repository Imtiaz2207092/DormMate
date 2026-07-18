<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::middleware('auth')->group(function () {
    Route::get('users', [ApiController::class, 'users']);
    Route::get('profile', [ApiController::class, 'profile']);
    Route::get('matches', [ApiController::class, 'matches']);
    Route::get('messages', [ApiController::class, 'messages']);
    Route::get('search', [ApiController::class, 'search']);
    Route::post('profile/update', [ApiController::class, 'updateProfile']);
});

<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['api', 'throttle:checkRateLimit'])->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('posts', PostController::class);
    Route::apiResource('comments', CommentController::class);
});

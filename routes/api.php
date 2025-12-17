<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\CommentController;

// Основная сущность: Card
Route::get('/cards', [CardController::class, 'index']);
Route::get('/cards/{card}', [CardController::class, 'show']);

// Вспомогательная сущность: Comment
Route::get('/comments', [CommentController::class, 'index']);
Route::get('/comments/{comment}', [CommentController::class, 'show']);

// Защищённые маршруты (требуют токен)
Route::middleware('auth:api')->group(function () {
    // Card
    Route::post('/cards', [CardController::class, 'store']);
    Route::put('/cards/{card}', [CardController::class, 'update']);
    Route::delete('/cards/{card}', [CardController::class, 'destroy']);
    Route::get('/my-cards', [CardController::class, 'myCards']);
    Route::get('/cards/category/{category}', [CardController::class, 'byCategory']);
    Route::get('/cards/search', [CardController::class, 'search']);

    // Comment
    Route::post('/comments', [CommentController::class, 'store']);
    Route::put('/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\FriendController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {

    Route::get('/me', fn () => auth()->user());

    Route::get('/cards', [CardController::class, 'index']);
    Route::post('/cards', [CardController::class, 'store']);
    Route::put('/cards/{card}', [CardController::class, 'update']);

    Route::get('/cards/{card}/comments', [CommentController::class, 'index']);
    Route::post('/cards/{card}/comments', [CommentController::class, 'store']);
    Route::put('/comments/{comment}', [CommentController::class, 'update']);

    Route::post('/friends/{user}', [FriendController::class, 'send']);
    Route::post('/friends/{user}/accept', [FriendController::class, 'accept']);
});

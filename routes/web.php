<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FriendController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return redirect()->route('cards.index');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/cards', [CardController::class, 'index'])->name('cards.index');
    Route::get('/cards/all', [CardController::class, 'all'])->name('cards.all');
    Route::get('/cards/friends', [CardController::class, 'friendsFeed'])->name('cards.friends');

    Route::get('/cards/create', [CardController::class, 'create'])->name('cards.create');
    Route::post('/cards', [CardController::class, 'store'])->name('cards.store');

    Route::get('/cards/trash', [CardController::class, 'trash'])->name('cards.trash');
    Route::post('/cards/{card}/restore', [CardController::class, 'restore'])->name('cards.restore');
    Route::delete('/cards/{card}/force-delete', [CardController::class, 'forceDelete'])->name('cards.forceDelete');

    Route::get('/cards/{card}', [CardController::class, 'show'])->name('cards.show');
    Route::get('/cards/{card}/edit', [CardController::class, 'edit'])->name('cards.edit');
    Route::put('/cards/{card}', [CardController::class, 'update'])->name('cards.update');
    Route::delete('/cards/{card}', [CardController::class, 'destroy'])->name('cards.destroy');

    Route::post('/cards/{card}/comments', [CommentController::class, 'store'])
        ->name('comments.store');

    Route::get('/friends', [FriendController::class, 'index'])->name('friends.index');
    Route::post('/friends/{user}', [FriendController::class, 'add'])->name('friends.add');
    Route::post('/friends/{user}/accept', [FriendController::class, 'accept'])->name('friends.accept');
    Route::delete('/friends/{user}', [FriendController::class, 'remove'])->name('friends.remove');

});

require __DIR__.'/auth.php';

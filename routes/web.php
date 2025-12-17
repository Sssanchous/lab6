<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FriendController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Главная страница
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Аутентификация (из auth.php)
require __DIR__.'/auth.php';

// Защищенные маршруты (требуют аутентификации)
Route::middleware('auth')->group(function () {
    
    // Дашборд (редирект на карточки)
    Route::get('/dashboard', function () {
        return redirect()->route('cards.index');
    })->name('dashboard');
    
    // ==================== ПРОФИЛЬ ПОЛЬЗОВАТЕЛЯ ====================
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
        
        // OAuth2 токены и клиенты
        Route::post('/token', [ProfileController::class, 'createToken'])->name('token.create');
        Route::delete('/token/{token}', [ProfileController::class, 'revokeToken'])->name('token.revoke');
    });
    
    // ==================== КАРТОЧКИ ====================
    Route::prefix('cards')->name('cards.')->group(function () {
        // Основные маршруты
        Route::get('/', [CardController::class, 'index'])->name('index');
        Route::get('/all', [CardController::class, 'all'])->name('all');
        Route::get('/friends', [CardController::class, 'friendsFeed'])->name('friends');
        
        Route::get('/create', [CardController::class, 'create'])->name('create');
        Route::post('/', [CardController::class, 'store'])->name('store');
        
        // Корзина и восстановление (мягкое удаление)
        Route::get('/trash', [CardController::class, 'trash'])->name('trash');
        Route::post('/{card}/restore', [CardController::class, 'restore'])->name('restore');
        Route::delete('/{card}/force-delete', [CardController::class, 'forceDelete'])->name('forceDelete');
        
        // Ресурсные маршруты
        Route::get('/{card}', [CardController::class, 'show'])->name('show');
        Route::get('/{card}/edit', [CardController::class, 'edit'])->name('edit');
        Route::put('/{card}', [CardController::class, 'update'])->name('update');
        Route::delete('/{card}', [CardController::class, 'destroy'])->name('destroy');
    });
    
    // ==================== КОММЕНТАРИИ ====================
    Route::post('/cards/{card}/comments', [CommentController::class, 'store'])
        ->name('comments.store');
    
    // ==================== ДРУЗЬЯ ====================
    Route::prefix('friends')->name('friends.')->group(function () {
        Route::get('/', [FriendController::class, 'index'])->name('index');
        Route::post('/{user}', [FriendController::class, 'add'])->name('add');
        Route::post('/{user}/accept', [FriendController::class, 'accept'])->name('accept');
        Route::delete('/{user}', [FriendController::class, 'remove'])->name('remove');
    });

});


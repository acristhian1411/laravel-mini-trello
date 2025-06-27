<?php

use App\Http\Controllers\Boards\BoardsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\TokenController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


// Route::resource('posts', PostController::class);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/boards', [BoardsController::class,'index'])->name('boards');
    Route::post('/boards', [BoardsController::class,'store'])->name('boards.store');
    Route::put('/boards/{board}', [BoardsController::class,'update'])->name('boards.update');
    Route::delete('/boards/{board}', [BoardsController::class,'destroy'])->name('boards.destroy');
    Route::get('/boards/{board}', [BoardsController::class,'show'])->name('boards.show');
    Route::get('boards-search', [BoardsController::class,'search'])->name('boards.search');
    Route::post('/token', [TokenController::class, 'generateToken'])->name('token.generate');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

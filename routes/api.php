<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Boards\BoardsController;
use App\Http\Controllers\Lists\ListsController;
use App\Http\Controllers\Api\Auth\JwtAuthController;
use App\Http\Controllers\Api\Auth\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('boards',[BoardsController::class,'index'])->name('boards.index');
    
});
Route::post('/login-jwt', [JwtAuthController::class, 'login']);

Route::group([
    'middleware'=>'api',
    'prefix'=>'auth'
], function($router){
    Route::post('login',[AuthController::class,'login'])->name('login');
    Route::post('logout',[AuthController::class,'logout'])->name('logout');
    Route::post('me',[AuthController::class,'me'])->name('me');
    Route::post('refresh',[AuthController::class,'refresh'])->name('refresh');
});

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [JwtAuthController::class, 'me']);
    Route::post('/logout', [JwtAuthController::class, 'logout']);

    // Tus rutas protegidas por JWT
    Route::get('/boards', [BoardsController::class, 'index']);
    Route::get('boards/{id}',[BoardsController::class,'show'])->name('boards.show');
    Route::post('boards',[BoardsController::class,'store'])->name('boards.store');
    Route::put('boards/{id}',[BoardsController::class,'update'])->name('boards.update');
    Route::delete('boards/{id}',[BoardsController::class,'destroy'])->name('boards.destroy');

    Route::get('/lists',[ListsController::class,'index']);
    Route::get('list/{id}',[ListController::class,'show'])->name('list.show');
    Route::post('list',[ListController::class,'store'])->name('list.store');
    Route::put('list/{id}',[ListController::class,'update'])->name('list.update');
    Route::delete('list/{id}',[ListController::class,'destroy'])->name('list.destroy');

});

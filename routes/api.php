<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Boards\BoardsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('boards',[BoardsController::class,'index'])->name('boards.index');
    Route::get('boards/{id}',[BoardsController::class,'show'])->name('boards.show');
    Route::post('boards',[BoardsController::class,'store'])->name('boards.store');
    Route::put('boards/{id}',[BoardsController::class,'update'])->name('boards.update');
    Route::delete('boards/{id}',[BoardsController::class,'destroy'])->name('boards.destroy');
});

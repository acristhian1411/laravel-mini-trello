<?php

use App\Http\Controllers\Role\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Boards\BoardsController;
use App\Http\Controllers\Lists\ListsController;
use App\Http\Controllers\Api\Auth\JwtAuthController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Permission\PermissionController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Log\LogController;
use App\Http\Controllers\Boards\BoardsReportController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('boards',[BoardsController::class,'index'])->name('boards.index');
    
});
// Route::post('/login-jwt', [JwtAuthController::class, 'login']);

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
    
    Route::get('/boards-report',[BoardsReportController::class,'generarReporte']);
    Route::get('/showboards-report/{id}',[BoardsReportController::class,'showBoard']);


    Route::get('roles',[RoleController::class,'index'])->middleware('role_or_permission:get.roles');
    Route::get('roles/{id}',[RoleController::class,'show'])->middleware('role_or_permission:get.roles');
    Route::post('roles',[RoleController::class,'store'])->middleware('role_or_permission:post.roles');
    Route::put('roles/{id}',[RoleController::class,'update'])->middleware('role_or_permission:put.roles');
    Route::delete('roles/{id}',[RoleController::class,'destroy'])->middleware('role_or_permission:delete.roles');
    Route::post('roles/{id}/sync-permissions', [RoleController::class, 'syncPermissions'])->middleware('role_or_permission:sync.permissions');
    Route::get('roles/{id}/permissions', [RoleController::class, 'getPermissions'])->middleware('role_or_permission:get.permissions');
    Route::post('roles/{id}/revoke-permission', [RoleController::class, 'revokePermissionFromRole'])->middleware('role_or_permission:revoke.permissions');
    
    
    Route::get('permissions',[PermissionController::class,'index'])->middleware('role_or_permission:get.permissions');
    Route::get('permissions/{id}',[PermissionController::class,'show'])->middleware('role_or_permission:get.permissions');
    Route::post('permissions',[PermissionController::class,'store'])->middleware('role_or_permission:post.permissions');
    Route::put('permissions/{id}',[PermissionController::class,'update'])->middleware('role_or_permission:put.permissions');
    Route::delete('permissions/{id}',[PermissionController::class,'destroy'])->middleware('role_or_permission:delete.permissions');

    Route::post('users/{id}/assign-role', [UserController::class, 'assignRole'])->middleware('role_or_permission:assign.role');
    Route::post('users/{id}/assign-permission', [UserController::class, 'assignPermission'])->middleware('role_or_permission:assign.permission');
    Route::post('users/{id}/revoke-role', [UserController::class, 'revokeRoleFromUser'])->middleware('role_or_permission:revoke.role');
    Route::post('users/{id}/revoke-permission', [UserController::class, 'revokePermissionFromUser'])->middleware('role_or_permission:revoke.permission');
    Route::get('users/{id}/permissions', [UserController::class, 'getPermissionsFromUser'])->middleware('role_or_permission:get.permissions');


    Route::get('/me', [JwtAuthController::class, 'me']);
    Route::post('/logout', [JwtAuthController::class, 'logout']);

    // Tus rutas protegidas por JWT
    Route::get('/boards', [BoardsController::class, 'index'])->middleware('role_or_permission:board.index')->description('Get boards list');
    Route::get('boards/{id}',[BoardsController::class,'show'])->name('boards.show')->middleware('role_or_permission:board.show')->description('Get board by id');
    Route::post('boards',[BoardsController::class,'store'])->name('boards.store')->middleware('role_or_permission:board.create')->description('Create new board');
    Route::put('boards/{id}',[BoardsController::class,'update'])->name('boards.update')->middleware('role_or_permission:board.edit')->description('Update board');
    Route::delete('boards/{id}',[BoardsController::class,'destroy'])->name('boards.destroy')->middleware('role_or_permission:board.delete')->description('Delete board');

    Route::get('logs', [LogController::class, 'index'])->middleware('role_or_permission:logs.index');

    Route::get('/secure-image/{path}', function ($path) {
        if (!request()->hasValidSignature()) {
            abort(403);
        }
    
        // $path = decrypt($path); // opcional, si querés más seguridad
        
        if (!Storage::disk('boards')->exists($path)) {
            abort(404);
        }
    
        return Response::file(
            Storage::disk('boards')->path($path)
        );
    })->name('boards.secure-image');

    Route::get('/lists',[ListsController::class,'index']);
    Route::get('lists/{id}',[ListsController::class,'show'])->name('list.show');
    Route::post('lists',[ListsController::class,'store'])->name('list.store');
    Route::put('lists/{id}',[ListsController::class,'update'])->name('list.update');
    Route::delete('lists/{id}',[ListsController::class,'destroy'])->name('list.destroy');

});

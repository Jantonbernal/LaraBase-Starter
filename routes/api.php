<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/* ----------  AUTH PUBLICA  ---------- */

Route::post('login', [AuthController::class, 'login']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('verify-code', [AuthController::class, 'verifyCode']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);

/* ----------  API PROTEGIDA (usuario ACTIVO) ---------- */
Route::middleware(['auth:sanctum', 'active'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('log', [LogController::class, 'index']);

    Route::apiResource('company', CompanyController::class)->except(['index', 'store', 'destroy']);

    // FILE UPLOAD
    Route::post('file', [FileController::class, 'uploadImage']);
    Route::post('files', [FileController::class, 'uploadFiles']);

    // MENUS
    Route::prefix('menu')->group(function () {
        Route::get('allMenus', [MenuController::class, 'allMenus']);
        Route::get('allSubMenus', [MenuController::class, 'allSubMenus']);
        Route::get('allSubMenusByMenuId/{menu}', [MenuController::class, 'allSubMenusByMenuId']);
        Route::get('subMenuId/{menu}', [MenuController::class, 'subMenuId']);
        Route::patch('{menu}/toggle', [MenuController::class, 'toggle']);
    });
    Route::apiResource('menu', MenuController::class)->except(['destroy']);

    // PERMISSION
    Route::prefix('permission')->group(function () {
        Route::get('permissionsForMenu', [PermissionController::class, 'permissionsForMenu']);
        Route::get('allPermissions', [PermissionController::class, 'allPermissions']);
        Route::patch('{permission}/toggle', [PermissionController::class, 'toggle']);
    });
    Route::apiResource('permission', PermissionController::class)->except(['destroy']);

    // ROLE
    Route::prefix('role')->group(function () {
        Route::get('allRoles', [RoleController::class, 'allRoles']);
        Route::patch('{role}/toggle', [RoleController::class, 'toggle']);
    });
    Route::apiResource('role', RoleController::class)->except(['destroy']);

    // USERS
    Route::prefix('user')->group(function () {
        Route::patch('{user}/toggle', [UserController::class, 'toggle']);
        Route::post('verify-code', [UserController::class, 'verifyCode']);
        Route::put('assignPermissions/{user}', [UserController::class, 'assignPermissions']);
        Route::get('allAccess', [UserController::class, 'allAccess']);
    });
    Route::apiResource('user', UserController::class)->except(['destroy']);
});

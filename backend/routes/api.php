<?php

use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::prefix('admin')->middleware(['auth:api', 'admin'])->group(function () {
    Route::apiResource('user', UserManagementController::class)->only('index', 'destroy');
});

Route::middleware(['auth:api'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('user', [UserManagementController::class, 'show']);
    Route::put('user', [UserManagementController::class, 'update']);

    Route::apiResource('item', ItemController::class);
    Route::post('item/{item}/status', [ItemController::class, 'updateStatus']);
    
    Route::apiResource('chat' , ChatController::class);
    Route::get('item/{id}/chat', [ChatController::class, 'showCommentsByItem']);
    Route::post('chat/{id}', [ChatController::class, 'store']);
});
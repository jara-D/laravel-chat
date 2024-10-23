<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ConnectionsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');
    Route::get('/chat/{chat}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{chat}', [ChatController::class, 'create'])->name('chat.create');
});

Route::middleware('auth')->group(function () {
    Route::get('/connect', [ConnectionsController::class, 'index'])->name('connect');
    Route::post('/connect/approve', [ConnectionsController::class, 'connectUsers'])->name('connect.connectUsers');
    Route::post('/connect/request', [ConnectionsController::class, 'request'])->name('connect.request');
});


require __DIR__.'/auth.php';

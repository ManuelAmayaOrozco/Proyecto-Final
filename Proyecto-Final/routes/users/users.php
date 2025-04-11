<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// RUTA PARA ENRUTAR /user/
Route::get('/login', [UserController::class, 'showLogin'])->name('login'); // IMPORTANTE PARA LARAVEL
Route::get('/register', [UserController::class, 'showRegister'])->name('user.showRegister');

Route::post('/login', [UserController::class, 'doLogin'])->name('user.doLogin');
Route::post('/register', [UserController::class, 'doRegister'])->name('user.doRegister');

Route::middleware(['auth'])->group(function(){

    Route::get('/profile', [UserController::class, 'showProfile'])->name('user.showProfile');
    Route::delete('/profile', [UserController::class, 'showDeleteProfile'])->name('user.showDeleteProfile');

    Route::delete('/logout/{id}', [UserController::class, 'logout'])->name('user.logout');

    Route::delete('/delete/{id}', [UserController::class, 'delete'])->name('user.delete');

});
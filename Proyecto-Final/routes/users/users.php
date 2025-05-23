<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// RUTA PARA ENRUTAR /user/
Route::get('/login', [UserController::class, 'showLogin'])->name('login'); // IMPORTANTE PARA LARAVEL
Route::get('/register', [UserController::class, 'showRegister'])->name('user.showRegister');

Route::post('/login', [UserController::class, 'doLogin'])->name('user.doLogin');
Route::post('/register', [UserController::class, 'doRegister'])->name('user.doRegister');

Route::get('/contact', [UserController::class, 'showContact'])->name('user.showContact');

Route::middleware(['auth'])->group(function(){

    Route::get('/profile', [UserController::class, 'showProfile'])->name('user.showProfile');

    Route::get('/adminMenu', [UserController::class, 'showAdminMenu'])->name('user.showAdminMenu');
    Route::put('/makeAdmin/{id}', [UserController::class, 'makeAdmin'])->name('user.makeAdmin');
    Route::put('/ban/{id}', [UserController::class, 'banUser'])->name('user.banUser');
    Route::put('/unban/{id}', [UserController::class, 'unbanUser'])->name('user.unbanUser');

    Route::get('/update/{id}', [UserController::class, 'showUpdateUser'])->name('user.showUpdateUser');
    Route::put('/update/{id}', [UserController::class, 'updateUser'])->name('user.updateUser');
    Route::get('/updatePassword/{id}', [UserController::class, 'showUpdatePassword'])->name('user.showUpdatePassword');
    Route::put('/updatePassword/{id}', [UserController::class, 'updatePassword'])->name('user.updatePassword');

    Route::delete('/logout/{id}', [UserController::class, 'logout'])->name('user.logout');

    Route::delete('/delete/{id}', [UserController::class, 'delete'])->name('user.delete');

    Route::post('/contact', [UserController::class, 'doContact'])->name('user.doContact');

});
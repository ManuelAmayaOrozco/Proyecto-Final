<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

// RUTA PARA ENRUTAR /post/
Route::get('/postlist', [PostController::class, 'showPosts'])->name('post.showPosts'); // IMPORTANTE PARA LARAVEL

Route::middleware(['auth'])->group(function(){

    Route::get('/register', [PostController::class, 'showRegisterPost'])->name('post.showRegisterPost');
    Route::post('/register', [PostController::class, 'doRegisterPost'])->name('post.doRegisterPost');

    Route::put('/like/{id}', [PostController::class, 'updateLike'])->name('post.like');

    Route::delete('/delete/{id}', [PostController::class, 'deletePost'])->name('post.delete');

});

Route::get('/fullPost/{id}', [PostController::class, 'showFullPost'])->name('post.showFullPost');
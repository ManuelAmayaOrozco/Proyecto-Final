<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

// RUTA PARA ENRUTAR /post/
Route::get('/postlist/{tagId?}', [PostController::class, 'showPosts'])->name('post.showPosts');

Route::middleware(['auth'])->group(function(){

    Route::get('/register', [PostController::class, 'showRegisterPost'])->name('post.showRegisterPost');
    Route::post('/register', [PostController::class, 'doRegisterPost'])->name('post.doRegisterPost');

    Route::put('/like/{id}', [PostController::class, 'updateLike'])->name('post.like');
    Route::put('/post/dislike/{id}', [PostController::class, 'removeLike'])->name('post.dislike');

    Route::put('/newFavorite/{id}', [PostController::class, 'newFavorite'])->name('post.newFavorite');
    Route::put('/removeFavorite/{id}', [PostController::class, 'removeFavorite'])->name('post.removeFavorite');

    Route::delete('/delete/{id}', [PostController::class, 'deletePost'])->name('post.delete');

});

Route::get('/fullPost/{id}', [PostController::class, 'showFullPost'])->name('post.showFullPost');

Route::post('/update-daily-post', [PostController::class, 'updateDailyPost'])->name('post.updateDaily');
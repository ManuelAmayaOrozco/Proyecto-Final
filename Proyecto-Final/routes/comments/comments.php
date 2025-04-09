<?php

use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){

    Route::get('/register/{id}', [CommentController::class, 'showRegisterComment'])->name('comment.showRegisterComment');
    Route::post('/register/{id}', [CommentController::class, 'doRegisterComment'])->name('comment.doRegisterComment');

});
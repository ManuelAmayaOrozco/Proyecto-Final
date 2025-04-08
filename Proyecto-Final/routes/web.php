<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PostController::class, 'showPosts'])->name('home');

Route::prefix('users')->group(base_path('routes/users/users.php'));
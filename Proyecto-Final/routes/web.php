<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Mews\Captcha\Captcha;
use Illuminate\Support\Facades\Storage;

Route::get('/', [PostController::class, 'showHome'])->name('home');

Route::prefix('users')->group(base_path('routes/users/users.php'));
Route::prefix('posts')->group(base_path('routes/posts/posts.php'));
Route::prefix('comments')->group(base_path('routes/comments/comments.php'));
Route::prefix('insects')->group(base_path('routes/insects/insects.php'));

Route::get('error-prueba-404', function () {
    abort(404);
})->name('dummy.404');

Route::get('captcha-reload', function () {
    return response()->json(['captcha' => captcha_src('flat')]);
})->name('captcha.reload');

Route::get('/check-manifest', function () {
    dd(file_exists(public_path('build/manifest.json')));
});

Route::get('/debug-images', function () {
    return Storage::disk('public')->files('insects');
});
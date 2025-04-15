<?php

use App\Http\Controllers\InsectController;
use Illuminate\Support\Facades\Route;

// RUTA PARA ENRUTAR /insect/
Route::get('/insectlist', [InsectController::class, 'showInsects'])->name('insect.showInsects');

Route::middleware(['auth'])->group(function(){

    Route::get('/register', [InsectController::class, 'showRegisterInsect'])->name('insect.showRegisterInsect');
    Route::post('/register', [InsectController::class, 'doRegisterInsect'])->name('insect.doRegisterInsect');

    Route::delete('/delete/{id}', [InsectController::class, 'deleteInsect'])->name('insect.deleteInsect');

});

Route::get('/fullInsect/{id}', [InsectController::class, 'showFullInsect'])->name('insect.showFullInsect');
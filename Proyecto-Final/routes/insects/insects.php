<?php

use App\Http\Controllers\InsectController;
use Illuminate\Support\Facades\Route;

// RUTA PARA ENRUTAR /insect/
Route::get('/insectlist', [InsectController::class, 'showInsects'])->name('insect.showInsects');

Route::middleware(['auth', 'verified'])->group(function(){

    Route::get('/register', [InsectController::class, 'showRegisterInsect'])->name('insect.showRegisterInsect');
    Route::post('/register', [InsectController::class, 'doRegisterInsect'])->name('insect.doRegisterInsect');

    Route::get('/update/{id}', [InsectController::class, 'showUpdateInsect'])->name('insect.showUpdateInsect');
    Route::put('/update/{id}', [InsectController::class, 'updateInsect'])->name('insect.updateInsect');

    Route::delete('/delete/{id}', [InsectController::class, 'deleteInsect'])->name('insect.deleteInsect');

});

Route::get('/fullInsect/{id}', [InsectController::class, 'showFullInsect'])->name('insect.showFullInsect');
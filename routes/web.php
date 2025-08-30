<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/form', [FormController::class, 'index'])->name('form.index');
    
});



Route::get('/form-builder', [FormController::class, 'index']);
Route::post('/form-builder/save', [FormController::class, 'store'])->name('form.store');
Route::get('/form-builder/{id}/preview', [FormController::class, 'preview'])->name('form.preview');
Route::get('/forms/{slug}', [FormController::class, 'public'])->name('form.public');


require __DIR__.'/auth.php';

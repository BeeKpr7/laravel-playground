<?php

use App\Livewire\Wizzard;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FilepondController;


Route::get('/', function () {
    return redirect()->route('test');
});
Route::view('/test', 'test')->name('test');

Route::view('/filepond', 'form.filepond')->name('filepond');
Route::post('/uploads/process', [FilepondController::class, 'upload'])->name('filepond.process');

Route::post('/uploads/store', [FilepondController::class, 'store'])->name('filepond.store');

Route::get('/wizzard', Wizzard::class)->name('wizzard');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

<?php

use App\Livewire\Wizzard;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestController;


Route::get('/', function () {
    return redirect()->route('test');
});
Route::get('/test', function () {
    return view('test');
})->name('test');

Route::get('/enum',[TestController::class, 'index'])->name('enum');

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

<?php

use App\Livewire\Wizzard;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FilepondController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});
Route::get('/enum',[TestController::class, 'index'])->name('enum');

Route::view('/home', 'test')->name('home');

Route::view('/hero-icons', 'test/hero-icons')->name('hero-icons');

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
    
    Route::resource('/posts', PostController::class);
});

require __DIR__.'/auth.php';

<?php

use App\Livewire\Wizzard;
use App\Livewire\PersonalBot;
use App\Livewire\Simulateur;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FilepondController;
use Illuminate\Support\Facades\App;

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
    return view('welcome');
});
Route::get('/enum', [TestController::class, 'index'])->name('enum');
Route::get('/objet', [TestController::class, 'objet'])->name('objet');
Route::get('/app', function () {
    dd(App::isProduction());
})->name('app');
Route::view('/test', 'test')->name('test');

Route::view('/hero-icons', 'test/hero-icons')->name('hero-icons');

Route::view('/filepond', 'form.filepond')->name('filepond');
Route::post('/uploads/process', [FilepondController::class, 'upload'])->name('filepond.process');

Route::post('/uploads/store', [FilepondController::class, 'store'])->name('filepond.store');

Route::get('/wizzard', Wizzard::class)->name('wizzard');
Route::get('/openai', PersonalBot::class)->name('openai');
Route::get('/simulateur', Simulateur::class)->name('simulateur');
Route::get('/eric', [TestController::class, 'eric'])->name('eric');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

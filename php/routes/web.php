<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::get('/home', [HomeController::class, 'index'])->middleware(['auth'])->name('dashboard');
Route::get('/tokens/create', [HomeController::class, 'create'])->middleware(['auth'])->name('home.token.create');
Route::get('/last-video', [HomeController::class, 'getLastVideo'])->middleware(['auth'])->name('home.last-video');
Route::post('/tokens/', [HomeController::class, 'store'])->middleware(['auth'])->name('home.token.store');
Route::get('/count/', [HomeController::class, 'count'])->middleware(['auth'])->name('home.stats.count');

Route::get('/test', [HomeController::class, 'test'])->middleware([])->name('test');

require __DIR__.'/auth.php';

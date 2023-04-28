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

Route::get('/home', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/test', [HomeController::class, 'test'])->middleware(['auth', 'verified'])->name('test');
Route::get('/api/graphic-data', [HomeController::class, 'graphic'])->middleware(['auth', 'verified'])->name('home.graphic.data');

require __DIR__.'/auth.php';

<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('graphic-data', [ApiController::class, 'graphic'])->middleware(['auth:sanctum'])->name('home.graphic.data');
Route::post('people-data', [ApiController::class, 'store'])->middleware(['auth:sanctum'])->name('home.people.store');
Route::post('videos', [ApiController::class, 'storeVideo'])->middleware(['auth:sanctum'])->name('home.video.store');
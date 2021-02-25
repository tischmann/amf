<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('contact')->group(function () {
    Route::get('/all', [ContactController::class, 'index']);
    Route::get('/{id}', [ContactController::class, 'get']);
    Route::put('/', [ContactController::class, 'insert']);
    Route::get('/update/{id}', [ContactController::class, 'update']);
    Route::get('/delete/{id}', [ContactController::class, 'delete']);
    Route::post('/{id}', [ContactController::class, 'update']);
    Route::delete('/{id}', [ContactController::class, 'delete']);
});

<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\PhoneController;
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

Route::prefix('contacts')->group(function () {
    Route::get('/', [ContactController::class, 'index']);
    Route::get('/{id}', [ContactController::class, 'select']);
    Route::put('/', [ContactController::class, 'insert']);
    Route::post('/{id}', [ContactController::class, 'update']);
    Route::delete('/{id}', [ContactController::class, 'delete']);
});

Route::prefix('phones')->group(function () {
    Route::get('/{id}', [PhoneController::class, 'select']);
    Route::put('/{contact_id}', [PhoneController::class, 'insert']);
    Route::post('/{id}', [PhoneController::class, 'update']);
    Route::delete('/{id}', [PhoneController::class, 'delete']);
});

Route::prefix('emails')->group(function () {
    Route::get('/{id}', [EmailController::class, 'select']);
    Route::put('/{contact_id}', [EmailController::class, 'insert']);
    Route::post('/{id}', [EmailController::class, 'update']);
    Route::delete('/{id}', [EmailController::class, 'delete']);
});

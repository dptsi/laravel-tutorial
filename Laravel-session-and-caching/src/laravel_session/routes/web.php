<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HumanssController;
use App\Http\Controllers\SessionController;
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

Route::get('/humans', [App\Http\Controllers\HumansController::class, 'index']);
Route::get('/humans/create', [App\Http\Controllers\HumansController::class, 'create']);
Route::post('/humans', [App\Http\Controllers\HumansController::class, 'store']);

Route::get('/push', [App\Http\Controllers\SessionController::class, 'push']);
Route::get('/store', [App\Http\Controllers\SessionController::class, 'store']);
Route::get('/show', [App\Http\Controllers\SessionController::class, 'show']);
Route::get('/showall', [App\Http\Controllers\SessionController::class, 'all']);
Route::get('/delete', [App\Http\Controllers\SessionController::class, 'delete']);
Route::get('/exists', [App\Http\Controllers\SessionController::class, 'exist']);
Route::get('/flush', [App\Http\Controllers\SessionController::class, 'flush']);
Route::get('/pull', [App\Http\Controllers\SessionController::class, 'pull']);
Route::get('/has', [App\Http\Controllers\SessionController::class, 'hasMis']);
Route::get('/inc', [App\Http\Controllers\SessionController::class, 'increment']);
Route::get('/dec', [App\Http\Controllers\SessionController::class, 'decrement']);
Route::get('/index', [App\Http\Controllers\SessionController::class, 'index']);
Route::get('/flash', [App\Http\Controllers\SessionController::class, 'flash']);
Route::get('/regen', [App\Http\Controllers\SessionController::class, 'regenerate']);
<?php

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

Route::get('cache/store','App\Http\Controllers\CacheController@storeCache');
Route::get('cache/show','App\Http\Controllers\CacheController@retrieveCache');
Route::get('cache/delete','App\Http\Controllers\CacheController@removeCache');
Route::get('cache/global','App\Http\Controllers\CacheController@cacheHelper');
Route::get('cache/lock','App\Http\Controllers\CacheController@lockCache');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

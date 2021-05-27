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

// Laravel-Route-Dasar 3
// Route::get('/pegawai', function () {
//     return view('welcome-pegawai');
// });

// Laravel-Route-Dasar 3.2
Route::view("/pegawai", "welcome-pegawai");

// Laravel-Route-Dasar 5
Route::redirect("/employee", "/pegawai");

// // Laravel-Route-Dengan-Parameter 1
// Route::get("/pegawai/{id}", function ($id) {
//     return "Pegawai dengan id: " . $id . ".";
// });

// Laravel-Route-Dengan-Parameter 2
// Route::get("/pegawai/{id}", function ($id) {
//     return "Pegawai dengan id: " . $id . ".";
// })->where('id', '[0-9]+');

Route::get("/pegawai/{id}", function ($id) {
    return "Pegawai dengan id: " . $id . ".";
})->whereNumber('id');

// Laravel-Route-Dengan-Parameter 2.2
// Route::get("/pegawai/{id}/city/{city}", function ($id, $city) {
//     return "Pegawai dengan id: " . $id . ", dengan kota: " . $city . ".";
// })->where(['id' => '[0-9]+', 'city' => '[a-z]+']);

// Route::get("/pegawai/{id}/city/{city}", function ($id, $city) {
//     return "Pegawai dengan id: " . $id . ", dengan kota: " . $city . ".";
// })->whereNumber('id')->whereAlpha('city');


// Laravel-Route-Dengan-Parameter 3
Route::prefix("/pegawai")->group(function () {
    Route::get("/view", function () {
        return "Pegawai Laravel.";
    });
    Route::get("/{id}", function ($id) {
        return "Pegawai dengan id: " . $id . ".";
    })->whereNumber('id');
    Route::get("/name/{name}", function ($name) {
        return "Pegawai dengan name: " . $name . ".";
    })->whereAlpha('name');
});

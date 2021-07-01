<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

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

Route::get('/dashboard', [AuthController::class, 'retrieve']) 
    ->middleware(['auth'])->name('dashboard');

Route::get('/check', [AuthController::class, 'check'])->name('check');

// MANUAL LOGIN
Route::get('/manual-login', [AuthController::class, 'show'])->name('manual login');
Route::post('/manual-login', [AuthController::class, 'authenticate']);

// HTTP AUTHENTICATION
Route::get('/profile', function() {
    return view('profile');
})->middleware('auth.basic')->name('profile');

// ================================================================

// PASSWORD CONFIRMATION
// the intended page
Route::get('/settings', function(){
    return view('settings');
})->middleware(['password.confirm'])->name('settings');

// password confirmation form
Route::get('/confirm-password', function () {
    return view('auth.confirm-password');
})->middleware('auth')->name('password.confirm');

// password confirming
Route::post('/confirm-password', function (Request $request) {
    if (! Hash::check($request->password, $request->user()->password)) {
        return back()->withErrors([
            'password' => ['The provided password does not match our records.']
        ]);
    }

    $request->session()->passwordConfirmed();

    return redirect()->intended('settings');
})->middleware(['auth', 'throttle:6,1'])->name('password.confirm');

// ================================================================

require __DIR__.'/auth.php';

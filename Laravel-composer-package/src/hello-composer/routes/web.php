<?php

use Illuminate\Support\Facades\Route;
use maximuse\HelloWorld\Http\Controllers\HelloController;

Route::get('/', [HelloController::class, 'show_hello'])->name('hello');
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

Route::get('/normal', function () {
    return view('view1');
});

Route::get('/dengan_facade', function () {
    return View::make('view1');
});

Route::get('/passing_array', function () {
    return view('view2', ['something' => 'Passing Value dengan Array']);
});

Route::get('/passing_with', function () {
    return view('view2')->with('something', 'Passing Value dengan With');
});

Route::get('/passing_compact', function () {
    $something = 'Passing Value dengan Compact';
    return view('view2', compact('something'));
});
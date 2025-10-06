<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::apiResource('todos', TodoController::class)->middleware('auth');

Route::delete('login', [LoginController::class, 'destroy']);

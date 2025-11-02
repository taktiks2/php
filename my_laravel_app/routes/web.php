<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TextController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', [TextController::class, 'index']);

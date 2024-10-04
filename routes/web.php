<?php

use Illuminate\Support\Facades\Route;

// Route::get('/logs', function () {
//     return view('logs');
// });

use App\Http\Controllers\LogController;

Route::get('/logs', [LogController::class, 'index']);

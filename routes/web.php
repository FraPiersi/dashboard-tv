<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index']);
Route::get('/api/meteo', [DashboardController::class, 'meteo']);
Route::get('/api/notizie', [DashboardController::class, 'notizie']);
Route::get('/api/video', [DashboardController::class, 'video']);
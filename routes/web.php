<?php

use App\Http\Controllers\RestaurantController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RestaurantController::class, 'index']);
Route::post('/search', [RestaurantController::class, 'search'])->name('search');


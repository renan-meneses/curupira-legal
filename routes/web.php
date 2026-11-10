<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HealthController;

Route::get('/healthz', HealthController::class)->name('healthz');
Route::view('/login', 'app')->name('login');
Route::middleware('auth')->get('/dashboard', fn () => view('app'))->name('dashboard');

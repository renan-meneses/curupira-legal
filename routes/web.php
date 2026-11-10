<?php

use Illuminate\Support\Facades\Route;

Route::view('/login', 'app')->name('login');
Route::middleware('auth')->get('/dashboard', fn () => view('app'))->name('dashboard');

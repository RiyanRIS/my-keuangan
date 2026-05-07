<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Dashboard placeholder
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Transactions
Route::get('/transactions/create', function () {
    return view('transactions.create');
})->name('transactions.create');

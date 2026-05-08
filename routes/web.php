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

// Transaction Modal (for dashboard)
Route::get('/transactions/create-modal', function () {
    return view('transactions.create');
    // return view('components.modals.transaction-create');
});

// Settings Pages
Route::get('/settings', function () {
    return view('settings.preferences');
})->name('settings');

Route::get('/account', function () {
    return view('settings.account');
})->name('account');

Route::get('/security', function () {
    return view('settings.security');
})->name('security');

Route::get('/backup', function () {
    return view('settings.backup');
})->name('backup');

Route::get('/feedback', function () {
    return view('settings.feedback');
})->name('feedback');

Route::get('/help', function () {
    return view('settings.help');
})->name('help');

// Settings Management Menu - Web Routes (UI only, API calls via AJAX)
Route::get('/settings-menu', function () {
    return view('settings.data-menu');
})->name('settings.data-menu');

Route::get('/settings/categories', function () {
    return view('settings.categories-list');
})->name('settings.categories');

Route::get('/settings/wallet-types', function () {
    return view('settings.wallet-types-list');
})->name('settings.wallet-types');

Route::get('/settings/wallets', function () {
    return view('settings.wallets-list');
})->name('settings.wallets');

Route::get('/settings/category/form/{id?}', function ($id = null) {
    return view('settings.category-form', ['id' => $id]);
})->name('settings.category.form');

Route::get('/settings/wallet-type/form/{id?}', function ($id = null) {
    return view('settings.wallet-type-form', ['id' => $id]);
})->name('settings.wallet-type.form');

Route::get('/settings/wallet/form/{id?}', function ($id = null) {
    return view('settings.wallet-form', ['id' => $id]);
})->name('settings.wallet.form');

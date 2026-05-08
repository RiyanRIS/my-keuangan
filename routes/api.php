<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\WalletTypeController;
use Illuminate\Support\Facades\Route;

// Public routes (no authentication required)
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
});

// Protected routes (authentication required)
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::get('/user', [AuthController::class, 'user'])->name('auth.user');
        Route::patch('/user', [AuthController::class, 'updateUser'])->name('auth.updateUser');
    });
    // Wallet Types routes
    Route::apiResource('wallet-types', WalletTypeController::class);

    // Wallets routes
    Route::get('/wallets/by-type/{wallet_type_id}', [WalletController::class, 'getByWalletType'])->name('wallets.byType');
    Route::apiResource('wallets', WalletController::class);

    // Categories routes
    Route::apiResource('categories', CategoryController::class);

    // Transactions routes
    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('transactions.index');
        Route::post('/income', [TransactionController::class, 'storeIncome'])->name('transactions.income');
        Route::post('/expense', [TransactionController::class, 'storeExpense'])->name('transactions.expense');
        Route::post('/transfer', [TransactionController::class, 'storeTransfer'])->name('transactions.transfer');
        Route::get('/wallet/{walletId}', [TransactionController::class, 'walletTransactions'])->name('transactions.wallet');
        Route::get('/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
        Route::post('/wallet/{walletId}/update-balance', [TransactionController::class, 'updateWalletBalance'])->name('transactions.updateBalance');
    });

    // Report routes
    Route::prefix('report')->group(function () {
        Route::get('/transactions', [ReportController::class, 'transactions'])->name('report.transactions');
        Route::get('/category-breakdown', [ReportController::class, 'categoryBreakdown'])->name('report.categoryBreakdown');
    });
});

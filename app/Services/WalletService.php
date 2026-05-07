<?php

namespace App\Services;

use App\Models\Wallet;
use Illuminate\Database\Eloquent\Collection;

class WalletService
{
    /**
     * Get all wallets for a user
     */
    public function getUserWallets(int $userId): Collection
    {
        return Wallet::where('user_id', $userId)
            ->with('walletType')
            ->get();
    }

    /**
     * Get wallet by ID and ensure it belongs to user
     */
    public function getWalletForUser(int $walletId, int $userId): ?Wallet
    {
        return Wallet::where('id', $walletId)
            ->where('user_id', $userId)
            ->with('walletType')
            ->first();
    }

    /**
     * Calculate balance for a wallet based on all transactions
     */
    public function calculateBalance(Wallet $wallet): float
    {
        $balance = 0;

        $wallet->transactions()
            ->orderBy('transaction_date')
            ->orderBy('created_at')
            ->each(function ($transaction) use (&$balance) {
                $balance = $transaction->balance_after;
            });

        return $balance;
    }

    /**
     * Verify wallet balance consistency - check transaction audit trail
     */
    public function verifyBalanceConsistency(Wallet $wallet): bool
    {
        $calculatedBalance = $this->calculateBalance($wallet);
        return $calculatedBalance == $wallet->balance;
    }

    /**
     * Get wallet current balance
     */
    public function getBalance(Wallet $wallet): float
    {
        return (float) $wallet->balance;
    }
}

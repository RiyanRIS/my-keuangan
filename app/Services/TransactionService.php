<?php

namespace App\Services;

use App\Enums\TransactionType;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class TransactionService
{
    protected WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Create income transaction
     * Rule: category_id wajib
     */
    public function createIncome(
        int $userId,
        int $walletId,
        int $categoryId,
        float $amount,
        ?string $note = null,
        ?\DateTime $transactionDate = null,
        ?string $transactionTime = null
    ): Transaction {
        // Validate category is income type
        $category = Category::where('id', $categoryId)
            ->where('user_id', $userId)
            ->first();

        if (!$category || $category->type->value !== 'income') {
            throw new InvalidArgumentException('Invalid income category');
        }

        return DB::transaction(function () use ($userId, $walletId, $categoryId, $amount, $note, $transactionDate, $transactionTime) {
            $wallet = Wallet::lockForUpdate()->find($walletId);

            if ($wallet->user_id !== $userId) {
                throw new InvalidArgumentException('Wallet does not belong to user');
            }

            $newBalance = $wallet->balance + $amount;
            $date = $transactionDate ? $transactionDate->format('Y-m-d') : now()->format('Y-m-d');

            $transaction = Transaction::create([
                'user_id' => $userId,
                'wallet_id' => $walletId,
                'category_id' => $categoryId,
                'type' => TransactionType::Income->value,
                'amount' => $amount,
                'balance_after' => $newBalance,
                'note' => $note,
                'transaction_date' => $date,
                'transaction_time' => $transactionTime,
            ]);

            // Update wallet balance
            $wallet->update(['balance' => $newBalance]);

            return $transaction->load('wallet', 'category');
        });
    }

    /**
     * Create expense transaction
     * Rule: category_id wajib
     */
    public function createExpense(
        int $userId,
        int $walletId,
        int $categoryId,
        float $amount,
        ?string $note = null,
        ?\DateTime $transactionDate = null,
        ?string $transactionTime = null
    ): Transaction {
        // Validate category is expense type
        $category = Category::where('id', $categoryId)
            ->where('user_id', $userId)
            ->first();

        if (!$category || $category->type->value !== 'expense') {
            throw new InvalidArgumentException('Invalid expense category');
        }

        return DB::transaction(function () use ($userId, $walletId, $categoryId, $amount, $note, $transactionDate, $transactionTime) {
            $wallet = Wallet::lockForUpdate()->find($walletId);

            if ($wallet->user_id !== $userId) {
                throw new InvalidArgumentException('Wallet does not belong to user');
            }

            $newBalance = $wallet->balance - $amount;
            $date = $transactionDate ? $transactionDate->format('Y-m-d') : now()->format('Y-m-d');

            $transaction = Transaction::create([
                'user_id' => $userId,
                'wallet_id' => $walletId,
                'category_id' => $categoryId,
                'type' => TransactionType::Expense->value,
                'amount' => $amount,
                'balance_after' => $newBalance,
                'note' => $note,
                'transaction_date' => $date,
                'transaction_time' => $transactionTime,
            ]);

            // Update wallet balance
            $wallet->update(['balance' => $newBalance]);

            return $transaction->load('wallet', 'category');
        });
    }

    /**
     * Create transfer between wallets
     * Rule: to_wallet_id wajib
     */
    public function createTransfer(
        int $userId,
        int $fromWalletId,
        int $toWalletId,
        float $amount,
        ?string $note = null,
        ?\DateTime $transactionDate = null,
        ?string $transactionTime = null
    ): Transaction {
        if ($fromWalletId === $toWalletId) {
            throw new InvalidArgumentException('Cannot transfer to the same wallet');
        }

        return DB::transaction(function () use ($userId, $fromWalletId, $toWalletId, $amount, $note, $transactionDate, $transactionTime) {
            // Lock both wallets to prevent race condition
            $fromWallet = Wallet::lockForUpdate()->find($fromWalletId);
            $toWallet = Wallet::lockForUpdate()->find($toWalletId);

            // Validate wallets belong to user
            if ($fromWallet->user_id !== $userId || $toWallet->user_id !== $userId) {
                throw new InvalidArgumentException('Wallets do not belong to user');
            }

            if ($fromWallet->balance < $amount) {
                throw new InvalidArgumentException('Insufficient balance in source wallet');
            }

            $date = $transactionDate ? $transactionDate->format('Y-m-d') : now()->format('Y-m-d');

            // Update source wallet
            $fromNewBalance = $fromWallet->balance - $amount;
            $fromWallet->update(['balance' => $fromNewBalance]);

            // Update destination wallet
            $toNewBalance = $toWallet->balance + $amount;
            $toWallet->update(['balance' => $toNewBalance]);

            // Create transaction record
            $transaction = Transaction::create([
                'user_id' => $userId,
                'wallet_id' => $fromWalletId,
                'to_wallet_id' => $toWalletId,
                'category_id' => null,
                'type' => TransactionType::Transfer->value,
                'amount' => $amount,
                'balance_after' => $fromNewBalance,
                'note' => $note,
                'transaction_date' => $date,
                'transaction_time' => $transactionTime,
            ]);

            return $transaction->load('wallet', 'toWallet');
        });
    }

    /**
     * Create adjustment transaction when user manually changes wallet balance
     * Rule: category_id NULL
     * If difference > 0: treated as income
     * If difference < 0: treated as expense
     */
    public function createAdjustment(
        int $userId,
        int $walletId,
        float $newBalance,
        ?string $note = null,
        ?\DateTime $transactionDate = null,
        ?string $transactionTime = null
    ): ?Transaction {
        return DB::transaction(function () use ($userId, $walletId, $newBalance, $note, $transactionDate, $transactionTime) {
            $wallet = Wallet::lockForUpdate()->find($walletId);

            if ($wallet->user_id !== $userId) {
                throw new InvalidArgumentException('Wallet does not belong to user');
            }

            $difference = $newBalance - $wallet->balance;

            // If no difference, don't create transaction
            if ($difference == 0) {
                return null;
            }

            $date = $transactionDate ? $transactionDate->format('Y-m-d') : now()->format('Y-m-d');

            $transaction = Transaction::create([
                'user_id' => $userId,
                'wallet_id' => $walletId,
                'category_id' => null,
                'type' => TransactionType::Adjustment->value,
                'amount' => abs($difference),
                'balance_after' => $newBalance,
                'note' => $note ?? 'Difference',
                'transaction_date' => $date,
                'transaction_time' => $transactionTime,
            ]);

            // Update wallet balance
            $wallet->update(['balance' => $newBalance]);

            return $transaction->load('wallet');
        });
    }

    /**
     * Get transaction history for wallet
     */
    public function getWalletTransactions(int $walletId, int $userId, int $limit = 50)
    {
        return Transaction::where('wallet_id', $walletId)
            ->where('user_id', $userId)
            ->with(['category', 'wallet', 'toWallet'])
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get all transactions for user
     */
    public function getUserTransactions(int $userId, int $limit = 100)
    {
        return Transaction::where('user_id', $userId)
            ->with(['category', 'wallet', 'toWallet'])
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}

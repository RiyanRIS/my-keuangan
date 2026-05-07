<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\StoreIncomeRequest;
use App\Http\Requests\StoreTransferRequest;
use App\Http\Requests\UpdateWalletBalanceRequest;
use App\Http\Resources\TransactionResource;
use App\Http\Responses\ApiResponse;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

class TransactionController extends BaseController
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Get all transactions for authenticated user
     */
    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $transactions = $this->transactionService->getUserTransactions($userId);

        return ApiResponse::success(
            TransactionResource::collection($transactions),
            'Transactions retrieved successfully'
        );
    }

    /**
     * Create income transaction
     */
    public function storeIncome(StoreIncomeRequest $request): JsonResponse
    {
        $userId = $request->user()->id;

        try {
            $transaction = $this->transactionService->createIncome(
                userId: $userId,
                walletId: $request->wallet_id,
                categoryId: $request->category_id,
                amount: $request->amount,
                note: $request->note,
                transactionDate: $request->transaction_date ? new \DateTime($request->transaction_date) : null,
                transactionTime: $request->transaction_time
            );

            return ApiResponse::created(
                new TransactionResource($transaction),
                'Income transaction created successfully'
            );
        } catch (InvalidArgumentException $e) {
            return ApiResponse::error($e->getMessage(), null, null, 422);
        }
    }

    /**
     * Create expense transaction
     */
    public function storeExpense(StoreExpenseRequest $request): JsonResponse
    {
        $userId = $request->user()->id;

        try {
            $transaction = $this->transactionService->createExpense(
                userId: $userId,
                walletId: $request->wallet_id,
                categoryId: $request->category_id,
                amount: $request->amount,
                note: $request->note,
                transactionDate: $request->transaction_date ? new \DateTime($request->transaction_date) : null,
                transactionTime: $request->transaction_time
            );

            return ApiResponse::created(
                new TransactionResource($transaction),
                'Expense transaction created successfully'
            );
        } catch (InvalidArgumentException $e) {
            return ApiResponse::error($e->getMessage(), null, null, 422);
        }
    }

    /**
     * Create transfer transaction
     */
    public function storeTransfer(StoreTransferRequest $request): JsonResponse
    {
        $userId = $request->user()->id;

        try {
            $transaction = $this->transactionService->createTransfer(
                userId: $userId,
                fromWalletId: $request->from_wallet_id,
                toWalletId: $request->to_wallet_id,
                amount: $request->amount,
                note: $request->note,
                transactionDate: $request->transaction_date ? new \DateTime($request->transaction_date) : null,
                transactionTime: $request->transaction_time
            );

            return ApiResponse::created(
                new TransactionResource($transaction),
                'Transfer transaction created successfully'
            );
        } catch (InvalidArgumentException $e) {
            return ApiResponse::error($e->getMessage(), null, null, 422);
        }
    }

    /**
     * Get transactions for specific wallet
     */
    public function walletTransactions(Request $request, int $walletId): JsonResponse
    {
        $userId = $request->user()->id;

        $transactions = $this->transactionService->getWalletTransactions(
            walletId: $walletId,
            userId: $userId
        );

        return ApiResponse::success(
            TransactionResource::collection($transactions),
            'Wallet transactions retrieved successfully'
        );
    }

    /**
     * Get single transaction
     */
    public function show(Request $request, Transaction $transaction): JsonResponse
    {
        if ($transaction->user_id !== $request->user()->id) {
            return ApiResponse::unauthorized('Unauthorized to view this transaction');
        }

        return ApiResponse::success(
            new TransactionResource($transaction->load('wallet', 'toWallet', 'category')),
            'Transaction retrieved successfully'
        );
    }

    /**
     * Update wallet balance (creates adjustment transaction)
     */
    public function updateWalletBalance(UpdateWalletBalanceRequest $request, int $walletId): JsonResponse
    {
        $userId = $request->user()->id;

        try {
            $transaction = $this->transactionService->createAdjustment(
                userId: $userId,
                walletId: $walletId,
                newBalance: $request->balance,
                note: $request->note,
                transactionDate: null,
                transactionTime: null
            );

            if (!$transaction) {
                return ApiResponse::success(
                    null,
                    'Wallet balance unchanged'
                );
            }

            return ApiResponse::success(
                new TransactionResource($transaction),
                'Wallet balance updated successfully with adjustment transaction'
            );
        } catch (InvalidArgumentException $e) {
            return ApiResponse::error($e->getMessage(), null, null, 422);
        }
    }
}

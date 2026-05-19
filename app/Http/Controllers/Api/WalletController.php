<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\WalletResource;
use App\Http\Responses\ApiResponse;
use App\Models\Wallet;
use App\Services\WalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WalletController extends BaseController
{
    protected WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Get all wallets for authenticated user
     */
    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $wallets = $this->walletService->getUserWallets($userId);

        return ApiResponse::success(
            WalletResource::collection($wallets),
            'Wallets retrieved successfully'
        );
    }

    /**
     * Get wallets by wallet type
     */
    public function getByWalletType(Request $request, int $walletTypeId): JsonResponse
    {
        $userId = $request->user()->id;

        $request->validate([
            'wallet_type_id' => 'integer|exists:wallet_types,id',
        ]);

        $wallets = Wallet::where('user_id', $userId)
            ->where('wallet_type_id', $walletTypeId)
            ->with('walletType')
            ->get();

        return ApiResponse::success(
            WalletResource::collection($wallets),
            'Wallets by type retrieved successfully'
        );
    }

    /**
     * Store a new wallet
     */
    public function store(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $request->validate([
            'wallet_type_id' => 'required|integer|exists:wallet_types,id',
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('wallets', 'name')->where('user_id', $userId),
            ],
            'balance' => 'nullable|numeric|min:0',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
        ]);

        $wallet = Wallet::create([
            'user_id' => $userId,
            'wallet_type_id' => $request->wallet_type_id,
            'name' => $request->name,
            'balance' => $request->balance ?? 0,
            'icon' => $request->icon,
            'color' => $request->color,
        ]);

        return ApiResponse::created(
            new WalletResource($wallet->load('walletType')),
            'Wallet created successfully'
        );
    }

    /**
     * Get single wallet
     */
    public function show(Request $request, Wallet $wallet): JsonResponse
    {
        if ($wallet->user_id !== $request->user()->id) {
            return ApiResponse::unauthorized('Unauthorized to view this wallet');
        }

        return ApiResponse::success(
            new WalletResource($wallet->load('walletType')),
            'Wallet retrieved successfully'
        );
    }

    /**
     * Update wallet
     */
    public function update(Request $request, Wallet $wallet): JsonResponse
    {
        if ($wallet->user_id !== $request->user()->id) {
            return ApiResponse::unauthorized('Unauthorized to update this wallet');
        }

        $userId = $request->user()->id;

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('wallets', 'name')
                    ->where('user_id', $userId)
                    ->ignore($wallet->id),
            ],
            'wallet_type_id' => 'required|integer|exists:wallet_types,id',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
        ]);

        $wallet->update([
            'name' => $request->name,
            'wallet_type_id' => $request->wallet_type_id,
            'icon' => $request->icon ?? $wallet->icon,
            'color' => $request->color ?? $wallet->color,
        ]);

        return ApiResponse::success(
            new WalletResource($wallet->load('walletType')),
            'Wallet updated successfully'
        );
    }

    /**
     * Delete wallet
     */
    public function destroy(Request $request, Wallet $wallet): JsonResponse
    {
        if ($wallet->user_id !== $request->user()->id) {
            return ApiResponse::unauthorized('Unauthorized to delete this wallet');
        }

        // Check if wallet has transactions
        if ($wallet->transactions()->exists()) {
            return ApiResponse::error(
                'Cannot delete wallet with transactions',
                null,
                null,
                422
            );
        }

        $wallet->delete();

        return ApiResponse::success(null, 'Wallet deleted successfully');
    }
}

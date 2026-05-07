<?php

namespace App\Http\Controllers\Api;

use App\Http\Responses\ApiResponse;
use App\Models\WalletType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WalletTypeController extends BaseController
{
    /**
     * Get all wallet types for authenticated user
     */
    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $walletTypes = WalletType::where('user_id', $userId)
            ->get();

        return ApiResponse::success(
            $walletTypes,
            'Wallet types retrieved successfully'
        );
    }

    /**
     * Store a new wallet type
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $userId = $request->user()->id;

        $walletType = WalletType::create([
            'user_id' => $userId,
            'name' => $request->name,
        ]);

        return ApiResponse::created(
            $walletType,
            'Wallet type created successfully'
        );
    }

    /**
     * Get single wallet type
     */
    public function show(Request $request, WalletType $walletType): JsonResponse
    {
        if ($walletType->user_id !== $request->user()->id) {
            return ApiResponse::unauthorized('Unauthorized to view this wallet type');
        }

        return ApiResponse::success(
            $walletType->load('wallets'),
            'Wallet type retrieved successfully'
        );
    }

    /**
     * Update wallet type
     */
    public function update(Request $request, WalletType $walletType): JsonResponse
    {
        if ($walletType->user_id !== $request->user()->id) {
            return ApiResponse::unauthorized('Unauthorized to update this wallet type');
        }

        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $walletType->update([
            'name' => $request->name,
        ]);

        return ApiResponse::success(
            $walletType,
            'Wallet type updated successfully'
        );
    }

    /**
     * Delete wallet type
     */
    public function destroy(Request $request, WalletType $walletType): JsonResponse
    {
        if ($walletType->user_id !== $request->user()->id) {
            return ApiResponse::unauthorized('Unauthorized to delete this wallet type');
        }

        $walletType->delete();

        return ApiResponse::success(null, 'Wallet type deleted successfully');
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'amount' => (float) $this->amount,
            'balance_after' => (float) $this->balance_after,
            'note' => $this->note,
            'transaction_date' => $this->transaction_date,
            'wallet' => [
                'id' => $this->wallet?->id,
                'name' => $this->wallet?->name,
            ],
            'to_wallet' => [
                'id' => $this->toWallet?->id,
                'name' => $this->toWallet?->name,
            ],
            'category' => [
                'id' => $this->category?->id,
                'name' => $this->category?->name,
                'icon' => $this->category?->icon,
                'color' => $this->category?->color,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

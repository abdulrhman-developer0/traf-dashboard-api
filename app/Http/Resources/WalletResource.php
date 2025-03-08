<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'balance'               => $this->balance,
            'total_profit'          => $this->total_profit,
            'bank_details'         => [
                'bank_name'             => $this->bank_name,
                'holder_name'           => $this->account_holder_name,
                'account_number'        => $this->account_number,
                'iban'                  => $this->iban,
            ],
            'transactions'   => TransactionResource::collection(
                $this->transactions()->latest()->paginate($request->input('page_size', 30))
            )
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'description'       => $this->description ?? 'نوع العملية',
            'transaction_type'  => $this->transaction_type,
            'status'            => $this->status,
            'amount'            => $this->amount,
            'created_at'        => $this->created_at,
            'transactionable'   => $this->transactionable
        ];
    }
}

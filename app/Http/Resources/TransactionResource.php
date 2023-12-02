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
            "id" => $this->id,
            "customer" => $this->customer,
            "transaction_code" => $this->transaction_code,
            "total_amount" => $this->total_amount,
            "total_price" => $this->total_price,
            "payment_method" => $this->payment_method,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "detail" => TransactionDetailResource::collection($this->details)
        ];
    }
}

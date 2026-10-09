<?php

namespace App\Services;

use App\Enums\StockMovementType;
use App\Models\Phone;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockService
{
    /**
     * Apply a stock change and record it in the ledger.
     *
     * For In / Out / Opening, $quantity is the number of units moved.
     * For Adjustment, $quantity is the counted on-hand total.
     */
    public function adjust(Phone $phone, StockMovementType $type, int $quantity, ?User $user = null, ?string $note = null): StockMovement
    {
        return DB::transaction(function () use ($phone, $type, $quantity, $user, $note) {
            // Lock the row so concurrent sales/adjustments can't race on the balance.
            $locked = Phone::whereKey($phone->getKey())->lockForUpdate()->firstOrFail();
            $current = $locked->stock_quantity;

            $change = match ($type) {
                StockMovementType::Opening, StockMovementType::In => $quantity,
                StockMovementType::Out => -$quantity,
                StockMovementType::Adjustment => $quantity - $current,
            };

            $balance = $current + $change;

            if ($balance < 0) {
                throw ValidationException::withMessages([
                    'quantity' => "Only {$current} unit(s) in stock; cannot remove {$quantity}.",
                ]);
            }

            $locked->stock_quantity = $balance;
            $locked->save();

            $phone->setRawAttributes($locked->getAttributes(), true);

            return $locked->stockMovements()->create([
                'user_id' => $user?->getKey(),
                'type' => $type,
                'quantity' => $change,
                'balance_after' => $balance,
                'note' => $note,
            ]);
        });
    }
}

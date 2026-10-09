<?php

namespace App\Enums;

enum StockStatus: string
{
    case InStock = 'in_stock';
    case Low = 'low';
    case Out = 'out';

    public static function for(int $quantity, int $reorderLevel): self
    {
        return match (true) {
            $quantity <= 0 => self::Out,
            $quantity <= $reorderLevel => self::Low,
            default => self::InStock,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::InStock => 'In stock',
            self::Low => 'Low stock',
            self::Out => 'Out of stock',
        };
    }

    /**
     * Semantic color token (see tailwind.config.js) used for badges and bars.
     */
    public function tone(): string
    {
        return match ($this) {
            self::InStock => 'success',
            self::Low => 'warning',
            self::Out => 'danger',
        };
    }
}

<?php

namespace App\Enums;

enum StockMovementType: string
{
    case Opening = 'opening';
    case In = 'in';
    case Out = 'out';
    case Adjustment = 'adjustment';

    public function label(): string
    {
        return match ($this) {
            self::Opening => 'Opening stock',
            self::In => 'Stock in',
            self::Out => 'Stock out',
            self::Adjustment => 'Stock count',
        };
    }

    /**
     * Types a user can pick when adjusting stock manually.
     *
     * @return list<self>
     */
    public static function manual(): array
    {
        return [self::In, self::Out, self::Adjustment];
    }
}

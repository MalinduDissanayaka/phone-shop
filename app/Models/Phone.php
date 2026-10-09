<?php

namespace App\Models;

use App\Enums\StockStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Phone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'cost_price',
        'reorder_level',
        'image',
        'category_id',
    ];

    // stock_quantity is intentionally not fillable: change it through StockService
    // so every change is recorded in the stock_movements ledger.

    protected function casts(): array
    {
        return [
            'stock_quantity' => 'integer',
            'reorder_level' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function stockStatus(): StockStatus
    {
        return StockStatus::for($this->stock_quantity, $this->reorder_level);
    }

    public function scopeWithStockStatus(Builder $query, StockStatus $status): Builder
    {
        return match ($status) {
            StockStatus::Out => $query->where('stock_quantity', '<=', 0),
            StockStatus::Low => $query->where('stock_quantity', '>', 0)->whereColumn('stock_quantity', '<=', 'reorder_level'),
            StockStatus::InStock => $query->whereColumn('stock_quantity', '>', 'reorder_level'),
        };
    }
}

<?php

namespace App\Models;

use App\Observers\SupplyObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(SupplyObserver::class)]
class Supply extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'inventory_id',
        'price',
        'unit',
        'quantity',
        'subtotal',
        'supplier_id',
        'supplied_at'
    ];

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}

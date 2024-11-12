<?php

namespace App\Models;

use App\Observers\CostObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(CostObserver::class)]
class Cost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'inventory_id',
        'quantity',
        'unit',
        'cost_at',
        'note'
    ];

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'brand',
        'quantity',
        'unit',
        'supplier_id'
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function supplies(): HasMany
    {
        return $this->hasMany(Supply::class);
    }

    public function lastSupply(): HasOne
    {
        return $this->supplies()->one()->latestOfMany();
    }

    public function costs(): HasMany
    {
        return $this->hasMany(Cost::class);
    }

    public function lastCost(): HasOne
    {
        return $this->costs()->one()->latestOfMany();
    }
}

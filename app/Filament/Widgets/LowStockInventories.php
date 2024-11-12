<?php

namespace App\Filament\Widgets;

use App\Models\Inventory;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LowStockInventories extends BaseWidget
{
    protected static ?int $sort = 2;

    protected static ?string $heading = 'Persediaan Stok Rendah';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Inventory::query()->where('quantity', '<', 10)->orderBy('quantity', 'asc'),
            )
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('supplier.name'),
            ])
            ->paginated([5, 10, 25, 50])
            ->defaultPaginationPageOption(5);;
    }
}

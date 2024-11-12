<?php

namespace App\Filament\Resources;

use App\Enums\InventoryUnit;
use App\Filament\Components as AppComponents;
use App\Filament\Resources\SupplyResource\Pages;
use App\Filament\Resources\SupplyResource\RelationManagers;
use App\Models\Inventory;
use App\Models\Supply;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SupplyResource extends Resource
{
    protected static ?string $model = Supply::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-on-square-stack';

    protected static ?int $navigationSort = 3;

    public static function getModelLabel(): string
    {
        return __('Supply');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Group::make()
                    ->disabledOn('edit')
                    ->schema([
                        Forms\Components\Section::make([
                            Forms\Components\Select::make('inventory_id')
                                ->relationship('inventory', 'name')
                                ->columnSpan(2)
                                ->preload()
                                ->searchable()
                                ->required()
                                ->live()
                                ->afterStateUpdated(
                                    function (string $state, callable $set) {
                                        if ($inventory = Inventory::find($state)) {
                                            $set('unit', $inventory->unit);
                                            $set('supplier_id', $inventory->supplier_id);
                                        }
                                    }
                                ),
                            Forms\Components\TextInput::make('code')
                                ->maxLength(255)
                                ->default(fake()->numerify('SUP######')),
                            Forms\Components\TextInput::make('price')
                                ->numeric()
                                ->live(onBlur: true)
                                ->afterStateUpdated(
                                    function (string $state, callable $set, callable $get) {
                                        $set('subtotal', $state * $get('quantity'));
                                    }
                                )
                                ->prefix('Rp'),
                            Forms\Components\TextInput::make('quantity')
                                ->default(1)
                                ->live(onBlur: true)
                                ->afterStateUpdated(
                                    function (string $state, callable $set, callable $get) {
                                        $set('subtotal', $state * $get('price'));
                                    }
                                )
                                ->numeric(),
                            Forms\Components\Select::make('unit')
                                ->options(InventoryUnit::class)
                                ->required(),
                            Forms\Components\Select::make('supplier_id')
                                ->relationship('supplier', 'name')
                                ->preload()
                                ->searchable()
                                ->required(),
                            Forms\Components\DateTimePicker::make('supplied_at')
                                ->default(now()),
                        ])->columns(3)
                    ])->columnSpan(['lg' => 2]),
                Forms\Components\Group::make([
                    Forms\Components\Section::make([
                        Forms\Components\TextInput::make('subtotal')
                            ->prefix('Rp')
                            ->numeric()
                            ->required(),
                    ]),
                    AppComponents\Forms\TimestampPlaceholder::make(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->groups(['inventory.name', 'supplier.name'])
            ->columns([
                AppComponents\Columns\IDColumn::make(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('inventory.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subtotal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('supplier.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('supplied_at')
                    ->dateTime()
                    ->sortable(),
                AppComponents\Columns\LastModifiedColumn::make(),
                AppComponents\Columns\CreatedAtColumn::make(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('inventory_id')
                    ->label('Inventory')
                    ->relationship('inventory', 'name')
                    ->preload()
                    ->searchable(),
                Tables\Filters\SelectFilter::make('supplier_id')
                    ->label('Supplier')
                    ->relationship('supplier', 'name')
                    ->preload()
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSupplies::route('/'),
            'create' => Pages\CreateSupply::route('/create'),
            'edit' => Pages\EditSupply::route('/{record}/edit'),
        ];
    }
}

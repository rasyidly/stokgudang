<?php

namespace App\Filament\Resources;

use App\Enums\InventoryUnit;
use App\Filament\Components as AppComponents;
use App\Filament\Resources\CostResource\Pages;
use App\Filament\Resources\CostResource\RelationManagers;
use App\Models\Inventory;
use App\Models\Cost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CostResource extends Resource
{
    protected static ?string $model = Cost::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-on-square-stack';

    protected static ?int $navigationSort = 4;

    public static function getModelLabel(): string
    {
        return __('Cost');
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
                                ->default(fake()->numerify('CST######')),
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
                            Forms\Components\DateTimePicker::make('cost_at')
                                ->default(now()),
                            Forms\Components\TextInput::make('note')
                                ->maxLength(255)
                                ->columnSpanFull(),
                        ])->columns(3),
                    ])->columnSpan(['lg' => 2]),
                Forms\Components\Group::make([
                    AppComponents\Forms\TimestampPlaceholder::make(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->groups(['inventory.name'])
            ->columns([
                AppComponents\Columns\IDColumn::make(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('inventory.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('note')
                    ->searchable(),
                AppComponents\Columns\LastModifiedColumn::make(),
                AppComponents\Columns\CreatedAtColumn::make(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('inventory_id')
                    ->label('Inventory')
                    ->relationship('inventory', 'name')
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
            'index' => Pages\ListCosts::route('/'),
            'create' => Pages\CreateCost::route('/create'),
            'edit' => Pages\EditCost::route('/{record}/edit'),
        ];
    }
}

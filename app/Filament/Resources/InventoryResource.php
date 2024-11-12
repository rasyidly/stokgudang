<?php

namespace App\Filament\Resources;

use App\Enums\InventoryUnit;
use App\Filament\Components as AppComponents;
use App\Filament\Resources\InventoryResource\Pages;
use App\Filament\Resources\InventoryResource\RelationManagers;
use App\Models\Inventory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoryResource extends Resource
{
    protected static ?string $model = Inventory::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getModelLabel(): string
    {
        return __('Inventory');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('brand')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('quantity')
                            ->numeric()
                            ->required(),
                        Forms\Components\Select::make('unit')
                            ->options(InventoryUnit::class)
                            ->required(),
                        Forms\Components\Select::make('supplier_id')
                            ->label('Default supplier')
                            ->relationship('supplier', 'name')
                            ->preload()
                            ->searchable()
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(200),
                                Forms\Components\TextInput::make('phone')
                                    ->tel()
                                    ->maxLength(200),
                                Forms\Components\TextInput::make('address')
                                    ->maxLength(200),
                            ])
                            ->createOptionAction(
                                fn(Forms\Components\Actions\Action $action) => $action
                                    ->modalHeading(__('Create new Supplier'))
                                    ->modalWidth(MaxWidth::Small)
                            ),
                    ])->columns(2)
                ])->columnSpan(['lg' => 2]),
                Forms\Components\Group::make([
                    AppComponents\Forms\TimestampPlaceholder::make(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->groups(['name', 'brand', 'supplier.name'])
            ->columns([
                AppComponents\Columns\IDColumn::make(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('brand')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit')
                    ->searchable(),
                Tables\Columns\TextColumn::make('supplier.name')
                    ->label('Default supplier')
                    ->sortable(),
                Tables\Columns\TextColumn::make('lastSupply.supplied_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('lastCost.cost_at')
                    ->dateTime(),
                AppComponents\Columns\LastModifiedColumn::make(),
                AppComponents\Columns\CreatedAtColumn::make(),
            ])
            ->filters([
                //
            ])
            ->actions([
                InventoryResource\Actions\AddSupplyAction::make(),
                InventoryResource\Actions\AddCostAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->hiddenLabel(),
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
            'index' => Pages\ListInventories::route('/'),
            'create' => Pages\CreateInventory::route('/create'),
            'edit' => Pages\EditInventory::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\InventoryResource\Actions;

use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class AddSupplyAction extends Action
{
    public static function make(?string $name = 'add_supply'): static
    {
        $static = app(static::class, [
            'name' => $name ?? static::getDefaultName(),
        ]);
        $static->configure();

        $static->icon('heroicon-o-plus-circle')
            ->color('success')
            ->label('Supply')
            ->modalWidth(MaxWidth::Large)
            ->form([
                Forms\Components\Group::make([
                    Forms\Components\Select::make('supplier_id')
                        ->relationship('supplier', 'name')
                        ->preload()
                        ->searchable()
                        ->required()
                        ->columnSpanFull()
                        ->autofocus(false)
                        ->default(fn(Model $record) => $record->supplier_id),
                    Forms\Components\TextInput::make('quantity')
                        ->default(1)
                        ->live(onBlur: true)
                        ->afterStateUpdated(
                            function (string $state, callable $set, callable $get) {
                                $set('subtotal', $state * $get('price'));
                            }
                        )
                        ->numeric()
                        ->suffix(fn(Model $record) => $record->unit),
                    Forms\Components\TextInput::make('price')
                        ->numeric()
                        ->live(onBlur: true)
                        ->afterStateUpdated(
                            function (string $state, callable $set, callable $get) {
                                $set('subtotal', $state * $get('quantity'));
                            }
                        )
                        ->default(fn(Model $record) => $record->lastSupply?->price)
                        ->prefix('Rp'),
                    Forms\Components\TextInput::make('subtotal')
                        ->prefix('Rp')
                        ->numeric()
                        ->required()
                        ->columnSpanFull()
                        ->default(fn(Model $record, callable $get) => $record->lastSupply?->price * $get('quantity')),
                ])->columns(2)
            ])
            ->action(function (Model $record, array $data) {
                $record->supplies()->create([
                    ...$data,
                    'code' => fake()->numerify('SUP######'),
                    'unit' => $record->unit,
                    'supplied_at' => now()
                ]);

                Notification::make()
                    ->success()
                    ->title(__('Supply added'))
                    ->send();
            });

        return $static;
    }
}

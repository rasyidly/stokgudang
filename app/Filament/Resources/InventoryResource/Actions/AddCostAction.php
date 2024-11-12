<?php

namespace App\Filament\Resources\InventoryResource\Actions;

use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class AddCostAction extends Action
{
    public static function make(?string $name = 'add_cost'): static
    {
        $static = app(static::class, [
            'name' => $name ?? static::getDefaultName(),
        ]);
        $static->configure();

        $static->icon('heroicon-o-minus-circle')
            ->color('danger')
            ->label('Cost')
            ->translateLabel()
            ->modalWidth(MaxWidth::Small)
            ->form([
                Forms\Components\Group::make([
                    Forms\Components\TextInput::make('quantity')
                        ->live(onBlur: true)
                        ->numeric()
                        ->suffix(fn(Model $record) => $record->unit),
                    Forms\Components\Textarea::make('note')
                        ->maxLength(255)
                        ->columnSpanFull(),
                ])
            ])
            ->action(function (Model $record, array $data) {
                $record->costs()->create([
                    ...$data,
                    'code' => fake()->numerify('CST######'),
                    'cost_at' => now()
                ]);

                Notification::make()
                    ->success()
                    ->title(__('Cost added'))
                    ->send();
            });

        return $static;
    }
}

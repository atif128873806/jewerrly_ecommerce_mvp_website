<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Items ordered';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                ImageColumn::make('image')
                    ->label('')
                    ->disk('webroot')
                    ->height(48)
                    ->width(40),
                TextColumn::make('name')
                    ->description(fn ($record) => $record->sku),
                TextColumn::make('unit_price')
                    ->label('Unit price')
                    ->formatStateUsing(fn ($state) => money($state)),
                TextColumn::make('qty')
                    ->label('Qty')
                    ->alignCenter(),
                TextColumn::make('line_total')
                    ->label('Total')
                    ->formatStateUsing(fn ($state) => money($state)),
            ])
            ->paginated(false);
    }
}
